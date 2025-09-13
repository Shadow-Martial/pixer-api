<?php

namespace App\Services;

use Flagsmith\Flagsmith;
use Flagsmith\Models\DefaultFlag;
use Illuminate\Support\Facades\Log;

class FlagsmithService
{
    private $flagsmith;

    public function __construct()
    {
        $environmentKey = config('flagsmith.environment_key');
        
        if (empty($environmentKey)) {
            Log::warning('Flagsmith environment key not configured');
            $this->flagsmith = null;
            return;
        }

        $this->flagsmith = new Flagsmith($environmentKey, [
            'api_url' => config('flagsmith.api_url'),
            'request_timeout_seconds' => config('flagsmith.request_timeout'),
            'enable_local_evaluation' => config('flagsmith.enable_local_evaluation'),
        ]);
    }

    /**
     * Check if a feature flag is enabled
     *
     * @param string $flagName
     * @param string|null $identity
     * @return bool
     */
    public function isFeatureEnabled(string $flagName, ?string $identity = null): bool
    {
        if (!$this->flagsmith) {
            return false;
        }

        try {
            if ($identity) {
                $flags = $this->flagsmith->getIdentityFlags($identity);
            } else {
                $flags = $this->flagsmith->getEnvironmentFlags();
            }

            return $flags->isFeatureEnabled($flagName);
        } catch (\Exception $e) {
            Log::error('Flagsmith error checking feature: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get feature flag value
     *
     * @param string $flagName
     * @param mixed $defaultValue
     * @param string|null $identity
     * @return mixed
     */
    public function getFeatureValue(string $flagName, $defaultValue = null, ?string $identity = null)
    {
        if (!$this->flagsmith) {
            return $defaultValue;
        }

        try {
            if ($identity) {
                $flags = $this->flagsmith->getIdentityFlags($identity);
            } else {
                $flags = $this->flagsmith->getEnvironmentFlags();
            }

            $flag = $flags->getFlag($flagName);
            return $flag ? $flag->getValue() : $defaultValue;
        } catch (\Exception $e) {
            Log::error('Flagsmith error getting feature value: ' . $e->getMessage());
            return $defaultValue;
        }
    }

    /**
     * Get all flags for an identity or environment
     *
     * @param string|null $identity
     * @return array
     */
    public function getAllFlags(?string $identity = null): array
    {
        if (!$this->flagsmith) {
            return [];
        }

        try {
            if ($identity) {
                $flags = $this->flagsmith->getIdentityFlags($identity);
            } else {
                $flags = $this->flagsmith->getEnvironmentFlags();
            }

            $result = [];
            foreach ($flags->getAllFlags() as $flag) {
                $result[$flag->getFeature()->getName()] = [
                    'enabled' => $flag->getEnabled(),
                    'value' => $flag->getValue(),
                ];
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Flagsmith error getting all flags: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Set traits for a user identity
     *
     * @param string $identity
     * @param array $traits
     * @return bool
     */
    public function setUserTraits(string $identity, array $traits): bool
    {
        if (!$this->flagsmith) {
            return false;
        }

        try {
            $this->flagsmith->setTrait($identity, $traits);
            return true;
        } catch (\Exception $e) {
            Log::error('Flagsmith error setting user traits: ' . $e->getMessage());
            return false;
        }
    }
}

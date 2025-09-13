<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flagsmith Environment Key
    |--------------------------------------------------------------------------
    |
    | Your Flagsmith environment key. You can find this in your Flagsmith
    | project settings under the environment you want to use.
    |
    */
    'environment_key' => env('FLAGSMITH_ENVIRONMENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Flagsmith API URL
    |--------------------------------------------------------------------------
    |
    | The URL for the Flagsmith API. Leave as default unless you're using
    | a self-hosted instance of Flagsmith.
    |
    */
    'api_url' => env('FLAGSMITH_API_URL', 'https://edge.api.flagsmith.com/api/v1/'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for requests to the Flagsmith API in seconds.
    |
    */
    'request_timeout' => env('FLAGSMITH_REQUEST_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | Enable Local Evaluation
    |--------------------------------------------------------------------------
    |
    | Enable local evaluation to reduce API calls and improve performance.
    |
    */
    'enable_local_evaluation' => env('FLAGSMITH_ENABLE_LOCAL_EVALUATION', false),
];

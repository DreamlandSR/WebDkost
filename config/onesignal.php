<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OneSignal App ID
    |--------------------------------------------------------------------------
    | Dapatkan dari: OneSignal Dashboard → Settings → Keys & IDs
    */
    'app_id' => env('ONESIGNAL_APP_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | OneSignal REST API Key
    |--------------------------------------------------------------------------
    | Dapatkan dari: OneSignal Dashboard → Settings → Keys & IDs
    | PENTING: Gunakan "REST API Key", bukan "User Auth Key"
    */
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY', ''),
];
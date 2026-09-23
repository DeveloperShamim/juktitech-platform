<?php

return [
    'google_drive' => [
        // Path to service account JSON (storage/app/google-drive-service-account.json)
        'service_account_path' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH', storage_path('app/google-drive-service-account.json')),
    ],
    'default_download_limit' => (int) env('DIGITAL_PRODUCT_DOWNLOAD_LIMIT', 3),
];

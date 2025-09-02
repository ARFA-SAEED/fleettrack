<?php

return [
    'application_name' => env('GOOGLE_APPLICATION_NAME', 'Laravel Google Sheets'),
    'spreadsheet_id' => env('GOOGLE_SHEET_ID', ''),
    'service' => env('GOOGLE_APPLICATION_CREDENTIALS', storage_path('app/google/google-service.json')),
    'scopes' => [
        'https://www.googleapis.com/auth/spreadsheets',
    ],
];
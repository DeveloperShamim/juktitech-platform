<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web document root (cPanel public_html / XAMPP htdocs)
    |--------------------------------------------------------------------------
    |
    | Uploads are mirrored to public_html/assets and project/assets when possible.
    | Leave WEB_ROOT_PATH empty on cPanel — auto-detect uses public_html.
    |
    */
    'web_root' => env('WEB_ROOT_PATH', dirname(base_path())),
];

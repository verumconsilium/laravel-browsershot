<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chrome Path
    |--------------------------------------------------------------------------
    |
    | Custom path to Chrome executable
    |
    */

    'chromePath' => env('BROWSERSHOT_CHROME', ''),

    /*
    |--------------------------------------------------------------------------
    | Node Binary
    |--------------------------------------------------------------------------
    |
    | Path where the node js binary is located
    | browsershot requires node >= 7.6.0
    |
    */

    'nodeBinary' => env('NODE_BINARY_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | NPM Binary
    |--------------------------------------------------------------------------
    |
    | The location of npm. Useful if npm cannot be accessed from the path
    |
    */

    'npmBinary' => env('NPM_BINARY_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Proxy Server
    |--------------------------------------------------------------------------
    |
    | Server where all http requests will be redirected to
    |
    */

    'proxyServer' => env('BROWSERSHOT_PROXYSERVER', ''),

    /*
    |--------------------------------------------------------------------------
    | No sandbox
    |--------------------------------------------------------------------------
    |
    | Whether Headless Chrome will be run with sandbox enabled
    |
    | Defaults to false
    |
    */

    'noSandbox' => env('BROWSERSHOT_NOSANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Directory for temporary files
    |--------------------------------------------------------------------------
    |
    | Browsershot will create all temporary files in this directory
    |
    | Defaults to empty
    |
    */
    'tempDir' => env('BROWSERSHOT_TEMPDIR', ''),

    /*
    |--------------------------------------------------------------------------
    | Additional Puppeteer options
    |--------------------------------------------------------------------------
    |
    | Addtional flags to run puppeteer with
    |
    |
    */
    'additionalOptions' => [],
];

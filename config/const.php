<?php

if (env('APP_ENV') == "local") {
    return[
        'HOST_NAME' => "http://localhost:8000"
    ];
}

if (env('APP_ENV') == 'production') {
    return[
        'HOST_NAME' => "https://paramaga.com"
    ];
}

if (env('APP_ENV') == 'dev') {
    return[
        'HOST_NAME' => "https://192.168.3.17:34534"
    ];
}
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
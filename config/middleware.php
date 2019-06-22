<?php

return [
    'App\Middleware\ShareValidationErrorsMiddleware',
    'App\Middleware\ClearValidationErrorsMiddleware',
    'App\Middleware\AuthenticateMiddleware',
    'App\Middleware\AuthenticateFromCookieMiddleware',
    'App\Middleware\CsrfGuardMiddleware',
];

<?php

if(! defined('ENVIRONMENT') )
{
    $domain = strtolower($_SERVER['HTTP_HOST']);

    switch($domain) {
        case 'fcubedigital.com' :
        case 'https://www.fcubedigital.com':
        case 'www.fcubedigital.com':
            define('ENVIRONMENT', 'production');
            break;

        case 'dev.fcubedigital.com' :
            //our staging server
            define('ENVIRONMENT', 'staging');
            break;

        default :
            define('ENVIRONMENT', 'development');
            break;
    }
}


<?php

$config = [];

/**
 * Username or password cant contain ":"
 * APACHE - SetEnv SDHVESELICE_APP_USER "user:password"
 */
if ($configurationString = getenv('SDHVESELICE_APP_USER')) {
    $credentials = explode(':', $configurationString);
    if (count($credentials) === 2) {

        $username = $credentials['0'];
        $password = $credentials['1'];

        $config = [
            'parameters' => [
                'user' => [
                    'username' => $username,
                    'password' => $password,
                ]
            ],
        ];
    }
}

return $config;
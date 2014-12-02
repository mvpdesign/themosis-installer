<?php

/*----------------------------------------------------*/
// $ENVIRONMENT environment vars
/*----------------------------------------------------*/
return array(
    /*----------------------------------------------------*/
    // Config
    /*----------------------------------------------------*/
    // Database
    'DB_NAME'           => '$DB_NAME',
    'DB_USER'           => '$DB_USER',
    'DB_PASSWORD'       => '$DB_PASSWORD',
    'DB_HOST'           => '$DB_HOST',

    // WordPress URLs
    'WP_HOME'           => '$WP_SITEURL',
    'WP_SITEURL'        => '$WP_SITEURL/wp',

    /*----------------------------------------------------*/
    // Authentication unique keys and salts
    /*----------------------------------------------------*/
    /**
     * @link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service
     */
    'AUTH_KEY'          => '$AUTH_KEY',
    'SECURE_AUTH_KEY'   => '$SECURE_AUTH_KEY',
    'LOGGED_IN_KEY'     => '$LOGGED_IN_KEY',
    'NONCE_KEY'         => '$NONCE_KEY',
    'AUTH_SALT'         => '$AUTH_SALT',
    'SECURE_AUTH_SALT'  => '$SECURE_AUTH_SALT',
    'LOGGED_IN_SALT'    => '$LOGGED_IN_SALT',
    'NONCE_SALT'        => '$NONCE_SALT'

);

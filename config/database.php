<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => null,
            'database' => database_path('database.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => true,
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'pelita_mesin_jahit',
            'username' => 'root',
            'password' => '',
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => null,
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'laravel',
            'username' => 'root',
            'password' => '',
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => null,
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'laravel',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => null,
            'host' => 'localhost',
            'port' => '1433',
            'database' => 'laravel',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [
        'client' => 'phpredis',
        'options' => [
            'cluster' => 'redis',
            'prefix' => Str::slug('laravel', '_').'_database_',
            'persistent' => false,
        ],
        'default' => [
            'url' => null,
            'host' => '127.0.0.1',
            'username' => null,
            'password' => null,
            'port' => '6379',
            'database' => '0',
        ],
        'cache' => [
            'url' => null,
            'host' => '127.0.0.1',
            'username' => null,
            'password' => null,
            'port' => '6379',
            'database' => '1',
        ],
    ],

];

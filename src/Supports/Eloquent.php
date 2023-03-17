<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connectors\ConnectionFactory;

class Eloquent
{
    protected static $previousResolver = null;

    /**
     * Init the database connection
     */
    public static function initNewDbConnection(): void
    {
        self::$previousResolver = Model::getConnectionResolver();

        // Create a new container instance
        $container = new Container();

        // Create a new connection factory and pass in the container
        $factory = new ConnectionFactory($container);

        // Define your database configuration options
        $config = [
            'driver' => 'sqlite',
            'database' => database_path('migrations/laravel-ready/url-shortener/' . Config::get('url-shortener.emoji.database', 'emojipadia-v1.0')),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ];

        // Use the factory to create a new connection
        $connection = $factory->make($config);

        // Set the connection resolver on the Eloquent ORM
        Model::setConnectionResolver(new class($connection) implements ConnectionResolverInterface
        {
            private $connection;

            public function __construct(Connection $connection)
            {
                $this->connection = $connection;
            }

            public function connection($name = null)
            {
                return $this->connection;
            }

            /**
             * Get the default connection name.
             * @return string
             */
            public function getDefaultConnection()
            {
            }

            /**
             * Set the default connection name.
             *
             * @param string $name
             * @return void
             */
            public function setDefaultConnection($name)
            {
            }
        });
    }

    /**
     * Restore the previous database connection
     * 
     * @return void
     */
    public static function restorePreviousDbConnection(): void
    {
        Model::setConnectionResolver(self::$previousResolver);
    }
}

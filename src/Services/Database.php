<?php

namespace Services;

use Doctrine\DBAL\{
    DriverManager,
    Configuration
};
use Obullo\Logger\SQLLogger\DoctrineDBAL as SQLLogger;
use League\Container\ServiceProvider\AbstractServiceProvider;

class Database extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'database'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $database = $container->get('loader')
            ->load('/config/%env%/database.yaml', true)
            ->database;

        $connectionParams = array(
            'url' => $database->url,
            'options'  => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        );
        $config  = new Configuration;
        $monolog = $container->get('loader')
            ->load('/config/%env%/monolog.yaml', true)
            ->monolog;

        if ($monolog->enabled)) {
            $config->setSQLLogger(new SQLLogger($container->get('logger')));
        }
        $conn = DriverManager::getConnection($connectionParams, $config);
        $container->share('database', $conn);
    }
}

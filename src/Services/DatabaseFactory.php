<?php

namespace Services;

use Doctrine\DBAL\{
    DriverManager,
    Configuration
};
use Obullo\Mvc\Logger\SQLLogger;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DatabaseFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $database = $container->get('loader')
            ->loadEnvConfigFile('database.yaml', true)
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
            ->loadEnvConfigFile('monolog.yaml', true)
            ->monolog;

        if ($monolog->enabled && $monolog->debug) {
            $config->setSQLLogger(new SQLLogger($container->get('logger')));
        }
        $conn = DriverManager::getConnection($connectionParams, $config);
        $conn->setFetchMode(\PDO::FETCH_OBJ);
        return $conn;
    }
}
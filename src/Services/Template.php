<?php

namespace Services;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Obullo\Mvc\View\PhpTemplate;

/**
 * Plates native php template - http://platesphp.com/
 */
class Template extends AbstractServiceProvider
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
        'template',
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

        $engine = new Engine(ROOT.'/App/View');
        $engine->setFileExtension('php');
        // $engine->addFolder('templates', ROOT.'/templates');
        $engine->loadExtension(new Asset('/public/assets/', true));

        $template = new PhpTemplate($engine);
        $template->setContainer($container);
        $template->registerFunctions();

        $container->share('template', $template);
    }
}

<?php

namespace Obullo\Mvc\View;

use Obullo\Mvc\Container\{
    ContainerAwareTrait,
    ContainerAwareInterface
};
use League\Plates\Engine;
use Obullo\Router\Generator;
use Obullo\Mvc\View\Plates\Template;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Plates template engine - http://platesphp.com/
 *
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class PhpTemplate implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Plates engine
     * 
     * @var object
     */
    protected $engine;

    /**
     * Constructor
     * 
     * @param Engine $engine plates engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Register Obullo helpers
     * 
     * @return void
     */
    public function registerFunctions()
    {
        $router = $this->getContainer()
            ->get('router');

        $this->engine->registerFunction('url', function (string $url, $params = array()) use($router) {
            $scheme = parse_url($url, PHP_URL_SCHEME);
            if ($scheme != null) {
                return $url;
            }
            $generator = new Generator($router->getCollection());
            return $generator->generate($url, $params);
        });
    }

    /**
     * Render template as html response
     * 
     * @param  string  $filename template name
     * @param  array   $data     template data
     * @param  integer $status   response status
     * @param  array   $headers  response headers
     * 
     * @return HtmlResponse
     */
    public function render(string $filename, $data = array(), $status = 200, array $headers = []) : Response
    {
        $html = $this->renderView($filename, $data);

        return new HtmlResponse($html, $status, $headers);
    }

    /**
     * Render view as string
     * 
     * @param  string $filename template name
     * @param  array  $data     template data
     * 
     * @return string
     */
    public function renderView(string $filename, $data = array()) : string
    {
        $template = new Template($this->engine, $filename);
        $template->setContainer($this->getContainer());

        return $template->render($data);
    }

    /**
     * Returns to template engine
     * 
     * @return object
     */
    public function getEngine() : Engine
    {
        return $this->engine;
    }
}

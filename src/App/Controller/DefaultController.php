<?php

namespace App\Controller;

use Obullo\Mvc\Controller;
use Zend\Diactoros\Response;
use Psr\Http\Message\RequestInterface as Request;

class DefaultController extends Controller
{
    public function index(Request $request) : Response
    {    	
        // $config = $this->loader->load('/config/%env%/database.yaml', true);
        // var_dump($config);

        return $this->template->render('welcome');
    }
}
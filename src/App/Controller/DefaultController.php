<?php

namespace App\Controller;

use Obullo\Mvc\Controller;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DefaultController extends Controller
{
	public function __construct()
	{
        // $this->translator->setLocale('es');
        // echo $this->translator->getLocale();

        // $this->translator->addTranslationFilePattern('PhpArray', ROOT, '/var/messages/%s/messages.php');
        // echo $this->translator->translate(100);

        // echo $this->translator->translate('Invalid type given. String expected', 'default', 'en');   
        // echo $this->translator->translate('Application Error');

		// $this->middleware->add('Translation')
  //           ->addArgument('methods', 'POST')
  //           ->addMethod('index');

        // $stack = $this->middleware->getStack();
        // print_r($stack);
	}

    public function index(Request $request) : Response
    {
        // $this->flash->warning('Message has been sent');

        // $this->response->redirect('/asdasd/');
        // $this->response->render(array $data);
        // 
        return $this->render('welcome');
        // $this->redirect();
        // return $this->response->render('welcome');
        // 
        // return $this->response->encode(['welcome']);
    }

    public function dummy()
    {
        return $this->render('welcome');
    }
}
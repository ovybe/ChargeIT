<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{


     #[Route("/helloworld", "Hello world")]


    public function helloworld(){

        return new Response(
            '<html><title>Hello!</title><h1>Hello world!</h1></html>'
        );
    }
}
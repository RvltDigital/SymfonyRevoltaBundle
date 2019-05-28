<?php

namespace RvltDigital\SymfonyRevoltaBundle\Service;

use RvltDigital\StaticDiBundle\StaticDI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Router;

class RouteForwarder
{
    public function forward(string $routeName, array $parameters = [], array $query = []): Response
    {
        /** @var Router $router */
        $router = StaticDI::get('router');

        $route = $router->getRouteCollection()->get($routeName);
        if ($route === null) {
            throw new \InvalidArgumentException("The route '{$routeName}' does not exist");
        }
        $parameters['_controller'] = $route->getDefault('_controller');

        $subRequest = StaticDI::getCurrentRequest()->duplicate($query, null, $parameters);

        /** @var HttpKernel $kernel */
        $kernel = StaticDI::get('http_kernel');

        return $kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}

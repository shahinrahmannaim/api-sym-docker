<?php

namespace ContainerMxS17Vl;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiDepartmentControllershowService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.9nJkjCo.App\Controller\API\ApiDepartmentController::show()' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.9nJkjCo.App\\Controller\\API\\ApiDepartmentController::show()'] = (new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'department' => ['privates', '.errored..service_locator.9nJkjCo.App\\Entity\\Department', NULL, 'Cannot autowire service ".service_locator.9nJkjCo": it needs an instance of "App\\Entity\\Department" but this type has been excluded in "config/services.yaml".'],
        ], [
            'department' => 'App\\Entity\\Department',
        ]))->withContext('App\\Controller\\API\\ApiDepartmentController::show()', $container);
    }
}

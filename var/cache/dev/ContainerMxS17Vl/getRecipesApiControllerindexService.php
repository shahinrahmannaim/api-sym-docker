<?php

namespace ContainerMxS17Vl;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getRecipesApiControllerindexService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.Tye6hqX.App\Controller\API\RecipesApiController::index()' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        $a = ($container->privates['.service_locator.Tye6hqX'] ?? $container->load('get_ServiceLocator_Tye6hqXService'));

        if (isset($container->privates['.service_locator.Tye6hqX.App\\Controller\\API\\RecipesApiController::index()'])) {
            return $container->privates['.service_locator.Tye6hqX.App\\Controller\\API\\RecipesApiController::index()'];
        }

        return $container->privates['.service_locator.Tye6hqX.App\\Controller\\API\\RecipesApiController::index()'] = $a->withContext('App\\Controller\\API\\RecipesApiController::index()', $container);
    }
}

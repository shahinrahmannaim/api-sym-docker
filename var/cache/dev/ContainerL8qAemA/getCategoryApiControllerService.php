<?php

namespace ContainerL8qAemA;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCategoryApiControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\API\adminApi\CategoryApiController' shared autowired service.
     *
     * @return \App\Controller\API\adminApi\CategoryApiController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/API/adminApi/CategoryApiController.php';

        $container->services['App\\Controller\\API\\adminApi\\CategoryApiController'] = $instance = new \App\Controller\API\adminApi\CategoryApiController();

        $instance->setContainer(($container->privates['.service_locator.QaaoWjx'] ?? $container->load('get_ServiceLocator_QaaoWjxService'))->withContext('App\\Controller\\API\\adminApi\\CategoryApiController', $container));

        return $instance;
    }
}

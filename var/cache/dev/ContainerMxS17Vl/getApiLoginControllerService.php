<?php

namespace ContainerMxS17Vl;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiLoginControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\API\ApiLoginController' shared autowired service.
     *
     * @return \App\Controller\API\ApiLoginController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/API/ApiLoginController.php';

        $container->services['App\\Controller\\API\\ApiLoginController'] = $instance = new \App\Controller\API\ApiLoginController();

        $instance->setContainer(($container->privates['.service_locator.QaaoWjx'] ?? $container->load('get_ServiceLocator_QaaoWjxService'))->withContext('App\\Controller\\API\\ApiLoginController', $container));

        return $instance;
    }
}

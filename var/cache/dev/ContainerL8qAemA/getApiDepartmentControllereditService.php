<?php

namespace ContainerL8qAemA;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiDepartmentControllereditService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.OPcyuNm.App\Controller\API\ApiDepartmentController::edit()' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        $a = ($container->privates['.service_locator.OPcyuNm'] ?? $container->load('get_ServiceLocator_OPcyuNmService'));

        if (isset($container->privates['.service_locator.OPcyuNm.App\\Controller\\API\\ApiDepartmentController::edit()'])) {
            return $container->privates['.service_locator.OPcyuNm.App\\Controller\\API\\ApiDepartmentController::edit()'];
        }

        return $container->privates['.service_locator.OPcyuNm.App\\Controller\\API\\ApiDepartmentController::edit()'] = $a->withContext('App\\Controller\\API\\ApiDepartmentController::edit()', $container);
    }
}

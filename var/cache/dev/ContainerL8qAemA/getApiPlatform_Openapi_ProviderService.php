<?php

namespace ContainerL8qAemA;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiPlatform_Openapi_ProviderService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'api_platform.openapi.provider' shared service.
     *
     * @return \ApiPlatform\OpenApi\State\OpenApiProvider
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/api-platform/core/src/OpenApi/State/OpenApiProvider.php';

        $a = ($container->privates['lexik_jwt_authentication.api_platform.openapi.factory'] ?? self::getLexikJwtAuthentication_ApiPlatform_Openapi_FactoryService($container));

        if (isset($container->privates['api_platform.openapi.provider'])) {
            return $container->privates['api_platform.openapi.provider'];
        }

        return $container->privates['api_platform.openapi.provider'] = new \ApiPlatform\OpenApi\State\OpenApiProvider($a);
    }
}

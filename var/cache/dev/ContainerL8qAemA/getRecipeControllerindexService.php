<?php

namespace ContainerL8qAemA;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getRecipeControllerindexService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.hMz.79t.App\Controller\Admin\RecipeController::index()' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.hMz.79t.App\\Controller\\Admin\\RecipeController::index()'] = (new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'repository' => ['privates', 'App\\Repository\\RecipeRepository', 'getRecipeRepositoryService', true],
            'categoryRepository' => ['privates', 'App\\Repository\\CategoryRepository', 'getCategoryRepositoryService', true],
        ], [
            'repository' => 'App\\Repository\\RecipeRepository',
            'categoryRepository' => 'App\\Repository\\CategoryRepository',
        ]))->withContext('App\\Controller\\Admin\\RecipeController::index()', $container);
    }
}

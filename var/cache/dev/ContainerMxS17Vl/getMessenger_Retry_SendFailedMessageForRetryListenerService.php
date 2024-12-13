<?php

namespace ContainerMxS17Vl;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getMessenger_Retry_SendFailedMessageForRetryListenerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'messenger.retry.send_failed_message_for_retry_listener' shared service.
     *
     * @return \Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/messenger/EventListener/SendFailedMessageForRetryListener.php';

        $a = ($container->services['event_dispatcher'] ?? self::getEventDispatcherService($container));

        if (isset($container->privates['messenger.retry.send_failed_message_for_retry_listener'])) {
            return $container->privates['messenger.retry.send_failed_message_for_retry_listener'];
        }

        return $container->privates['messenger.retry.send_failed_message_for_retry_listener'] = new \Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener(($container->privates['.service_locator.Mghweq_'] ?? $container->load('get_ServiceLocator_MghweqService')), new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'async' => ['privates', 'messenger.retry.multiplier_retry_strategy.async', 'getMessenger_Retry_MultiplierRetryStrategy_AsyncService', true],
            'failed' => ['privates', 'messenger.retry.multiplier_retry_strategy.failed', 'getMessenger_Retry_MultiplierRetryStrategy_FailedService', true],
            'sync' => ['privates', 'messenger.retry.multiplier_retry_strategy.sync', 'getMessenger_Retry_MultiplierRetryStrategy_SyncService', true],
        ], [
            'async' => '?',
            'failed' => '?',
            'sync' => '?',
        ]), ($container->privates['monolog.logger.messenger'] ?? $container->load('getMonolog_Logger_MessengerService')), $a);
    }
}

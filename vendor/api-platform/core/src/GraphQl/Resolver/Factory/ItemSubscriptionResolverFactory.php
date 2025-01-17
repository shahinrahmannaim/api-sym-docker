<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\GraphQl\Resolver\Factory;

use ApiPlatform\GraphQl\Resolver\Stage\ReadStageInterface;
use ApiPlatform\GraphQl\Resolver\Stage\SecurityStageInterface;
use ApiPlatform\GraphQl\Resolver\Stage\SerializeStageInterface;
use ApiPlatform\GraphQl\Subscription\MercureSubscriptionIriGeneratorInterface;
use ApiPlatform\GraphQl\Subscription\SubscriptionManagerInterface;
use ApiPlatform\Metadata\GraphQl\Operation;
use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Metadata\Util\ClassInfoTrait;
use ApiPlatform\Metadata\Util\CloneTrait;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Creates a function resolving a GraphQL subscription of an item.
 *
 * @author Alan Poulain <contact@alanpoulain.eu>
 *
 * @deprecated
 */
final class ItemSubscriptionResolverFactory implements ResolverFactoryInterface
{
    use ClassInfoTrait;
    use CloneTrait;

    public function __construct(private readonly ReadStageInterface $readStage, private readonly SecurityStageInterface $securityStage, private readonly SerializeStageInterface $serializeStage, private readonly SubscriptionManagerInterface $subscriptionManager, private readonly ?MercureSubscriptionIriGeneratorInterface $mercureSubscriptionIriGenerator)
    {
    }

    public function __invoke(?string $resourceClass = null, ?string $rootClass = null, ?Operation $operation = null, ?PropertyMetadataFactoryInterface $propertyMetadataFactory = null): callable
    {
        return function (?array $source, array $args, $context, ResolveInfo $info) use ($resourceClass, $rootClass, $operation): ?array {
            if (null === $resourceClass || null === $operation) {
                return null;
            }

            $resolverContext = ['source' => $source, 'args' => $args, 'info' => $info, 'is_collection' => false, 'is_mutation' => false, 'is_subscription' => true];

            $item = ($this->readStage)($resourceClass, $rootClass, $operation, $resolverContext);
            if (null !== $item && !\is_object($item)) {
                throw new \LogicException('Item from read stage should be a nullable object.');
            }
            ($this->securityStage)($resourceClass, $operation, $resolverContext + [
                'extra_variables' => [
                    'object' => $item,
                ],
            ]);

            $result = ($this->serializeStage)($item, $resourceClass, $operation, $resolverContext);

            $subscriptionId = $this->subscriptionManager->retrieveSubscriptionId($resolverContext, $result);

            if ($subscriptionId && ($mercure = $operation->getMercure())) {
                if (!$this->mercureSubscriptionIriGenerator) {
                    throw new \LogicException('Cannot use Mercure for subscriptions when MercureBundle is not installed. Try running "composer require mercure".');
                }

                $hub = \is_array($mercure) ? ($mercure['hub'] ?? null) : null;
                $result['mercureUrl'] = $this->mercureSubscriptionIriGenerator->generateMercureUrl($subscriptionId, $hub);
            }

            return $result;
        };
    }
}

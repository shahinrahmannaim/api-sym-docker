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

namespace ApiPlatform\GraphQl\Resolver\Stage;

use ApiPlatform\Metadata\GraphQl\Operation;

/**
 * Deserialize stage of GraphQL resolvers.
 *
 * @author Alan Poulain <contact@alanpoulain.eu>
 *
 * @deprecated
 */
interface DeserializeStageInterface
{
    public function __invoke(?object $objectToPopulate, string $resourceClass, Operation $operation, array $context): ?object;
}

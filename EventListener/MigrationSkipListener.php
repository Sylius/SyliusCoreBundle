<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\EventListener;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Event\MigrationsVersionEventArgs;
use Doctrine\Migrations\Version\Direction;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\MigrationSkipInterface;

final class MigrationSkipListener
{
    public function __construct(private readonly DependencyFactory $dependencyFactory)
    {
    }

    public function onMigrationsVersionSkipped(MigrationsVersionEventArgs $event): void
    {
        $plan = $event->getPlan();
        $result = $plan->getResult();

        if (
            $plan->getDirection() === Direction::UP &&
            $plan->getMigration() instanceof MigrationSkipInterface &&
            $result->isSkipped()
        ) {
            $metadataStorage = $this->dependencyFactory->getMetadataStorage();
            $metadataStorage->complete($result);
        }
    }
}

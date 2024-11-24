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
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class PostgreSQLSkipMigration
{
    public function __construct(
        private readonly DependencyFactory $dependencyFactory,
    ) {
    }

    public function onMigrationsVersionSkipped(MigrationsVersionEventArgs $event): void
    {
        $migration = $event->getPlan()->getMigration();
        $result = $event->getPlan()->result;
        $direction = $event->getPlan()->getDirection();

        if (
            $direction === Direction::UP &&
            $migration instanceof AbstractPostgreSQLMigration &&
            $result->isSkipped() &&
            $migration->isMarkCompletedWhenSkip()
        ) {
            $metadataStorage = $this->dependencyFactory->getMetadataStorage();
            $metadataStorage->complete($event->getPlan()->result);
        }
    }
}

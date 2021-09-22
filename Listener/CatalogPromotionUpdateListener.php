<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Processor\CatalogPromotionReprocessorInterface;
use Sylius\Component\Promotion\Event\CatalogPromotionUpdated;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogPromotionUpdateListener
{
    private CatalogPromotionReprocessorInterface $catalogPromotionReprocessor;

    private RepositoryInterface $catalogPromotionRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        CatalogPromotionReprocessorInterface $catalogPromotionReprocessor,
        RepositoryInterface $catalogPromotionRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->catalogPromotionReprocessor = $catalogPromotionReprocessor;
        $this->catalogPromotionRepository = $catalogPromotionRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(CatalogPromotionUpdated $event): void
    {
        if (null === $this->catalogPromotionRepository->findOneBy(['code' => $event->code])) {
            return;
        }

        $this->catalogPromotionReprocessor->reprocess();

        $this->entityManager->flush();
    }
}

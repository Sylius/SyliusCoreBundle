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

use Sylius\Bundle\CoreBundle\Processor\CatalogPromotionProcessorInterface;
use Sylius\Component\Promotion\Event\CatalogPromotionUpdated;
use Sylius\Component\Promotion\Model\CatalogPromotionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogPromotionUpdateListener
{
    private CatalogPromotionProcessorInterface $catalogPromotionProcessor;

    private RepositoryInterface $catalogPromotionRepository;

    public function __construct(
        CatalogPromotionProcessorInterface $catalogPromotionProcessor,
        RepositoryInterface $catalogPromotionRepository
    ) {
        $this->catalogPromotionProcessor = $catalogPromotionProcessor;
        $this->catalogPromotionRepository = $catalogPromotionRepository;
    }

    public function __invoke(CatalogPromotionUpdated $event): void
    {
        /** @var CatalogPromotionInterface|null $catalogPromotion */
        $catalogPromotion = $this->catalogPromotionRepository->findOneBy(['code' => $event->code]);
        if ($catalogPromotion === null) {
            return;
        }

        $this->catalogPromotionProcessor->process($catalogPromotion);
    }
}

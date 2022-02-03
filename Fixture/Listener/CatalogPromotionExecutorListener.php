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

namespace Sylius\Bundle\CoreBundle\Fixture\Listener;

use Sylius\Bundle\CoreBundle\Fixture\CatalogPromotionFixture;
use Sylius\Bundle\CoreBundle\Processor\AllProductVariantsCatalogPromotionsProcessorInterface;
use Sylius\Bundle\CoreBundle\Processor\CatalogPromotionStateProcessorInterface;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;
use Sylius\Component\Promotion\Repository\CatalogPromotionRepositoryInterface;

final class CatalogPromotionExecutorListener extends AbstractListener implements AfterFixtureListenerInterface
{
    public function __construct(
        private AllProductVariantsCatalogPromotionsProcessorInterface $allCatalogPromotionsProcessor,
        private CatalogPromotionStateProcessorInterface $catalogPromotionStateProcessor,
        private CatalogPromotionRepositoryInterface $catalogPromotionsRepository,
        private iterable $defaultCriteria = []
    ) {
    }

    public function afterFixture(FixtureEvent $fixtureEvent, array $options): void
    {
        if (!$fixtureEvent->fixture() instanceof CatalogPromotionFixture) {
            return;
        }

        $this->allCatalogPromotionsProcessor->process();

        $catalogPromotions = $this->catalogPromotionsRepository->findByCriteria($this->defaultCriteria);

        foreach ($catalogPromotions as $catalogPromotion) {
            $this->catalogPromotionStateProcessor->process($catalogPromotion);
        }
    }

    public function getName(): string
    {
        return 'catalog_promotion_processor_executor';
    }
}

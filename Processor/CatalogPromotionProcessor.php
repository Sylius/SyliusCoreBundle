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

namespace Sylius\Bundle\CoreBundle\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Applicator\CatalogPromotionApplicatorInterface;
use Sylius\Component\Core\Model\CatalogPromotionInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Provider\CatalogPromotionVariantsProviderInterface;

final class CatalogPromotionProcessor implements CatalogPromotionProcessorInterface
{
    private CatalogPromotionVariantsProviderInterface $catalogPromotionVariantsProvider;

    private CatalogPromotionApplicatorInterface $catalogPromotionApplicator;

    public function __construct(
        CatalogPromotionVariantsProviderInterface $catalogPromotionVariantsProvider,
        CatalogPromotionApplicatorInterface $catalogPromotionApplicator
    ) {
        $this->catalogPromotionVariantsProvider = $catalogPromotionVariantsProvider;
        $this->catalogPromotionApplicator = $catalogPromotionApplicator;
    }

    public function process(CatalogPromotionInterface $catalogPromotion): void
    {
        $variants = $this->catalogPromotionVariantsProvider->provideEligibleVariants($catalogPromotion);
        if (empty($variants)) {
            return;
        }

        /** @var ProductVariantInterface $variant */
        foreach ($variants as $variant) {
            $this->catalogPromotionApplicator->applyCatalogPromotion($variant, $catalogPromotion);
        }
    }
}

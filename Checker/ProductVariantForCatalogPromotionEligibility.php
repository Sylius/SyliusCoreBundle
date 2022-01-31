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

namespace Sylius\Bundle\CoreBundle\Checker;

use Sylius\Bundle\CoreBundle\Provider\ForVariantInCatalogPromotionScopeCheckerProviderInterface;
use Sylius\Component\Core\Model\CatalogPromotionInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\CatalogPromotionScopeInterface;

final class ProductVariantForCatalogPromotionEligibility implements ProductVariantForCatalogPromotionEligibilityInterface
{
    public function __construct(private ForVariantInCatalogPromotionScopeCheckerProviderInterface $checkerProvider)
    {
    }

    public function isApplicableOnVariant(CatalogPromotionInterface $promotion, ProductVariantInterface $variant): bool
    {
        /** @var CatalogPromotionScopeInterface $scope */
        foreach ($promotion->getScopes() as $scope) {
            $checker = $this->checkerProvider->provide($scope);

            if ($checker->inScope($scope, $variant)) {
                return true;
            }
        }

        return false;
    }
}

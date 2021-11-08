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

namespace Sylius\Bundle\CoreBundle\Provider;

use Sylius\Component\Core\Model\CatalogPromotionScopeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

final class ForProductsScopeVariantsProvider implements VariantsProviderInterface
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function supports(CatalogPromotionScopeInterface $catalogPromotionScopeType): bool
    {
        return $catalogPromotionScopeType->getType() === CatalogPromotionScopeInterface::TYPE_FOR_PRODUCTS;
    }

    public function provideEligibleVariants(CatalogPromotionScopeInterface $scope): array
    {
        $configuration = $scope->getConfiguration();
        Assert::keyExists($configuration, 'products', 'This rule should have configured products');

        $variants = [];
        /** @var string $productCode */
        foreach ($scope->getConfiguration()['products'] as $productCode) {
            /** @var ProductInterface|null $product */
            $product = $this->productRepository->findOneBy(['code' => $productCode]);
            if (null === $product) {
                continue;
            }

            $variants = array_merge($variants, $product->getVariants()->toArray());
        }

        return $variants;
    }
}

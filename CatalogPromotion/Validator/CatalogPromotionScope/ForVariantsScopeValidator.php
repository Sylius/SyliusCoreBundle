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

namespace Sylius\Bundle\CoreBundle\CatalogPromotion\Validator\CatalogPromotionScope;

use Sylius\Bundle\PromotionBundle\Validator\CatalogPromotionScope\ScopeValidatorInterface;
use Sylius\Bundle\PromotionBundle\Validator\Constraints\CatalogPromotionScope;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Webmozart\Assert\Assert;

trigger_deprecation(
    'sylius/core-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0, use the usual symfony logic for validation.',
    ForVariantsScopeValidator::class,
);
final class ForVariantsScopeValidator implements ScopeValidatorInterface
{
    public function __construct(private ProductVariantRepositoryInterface $variantRepository)
    {
    }

    public function validate(array $configuration, Constraint $constraint, ExecutionContextInterface $context): void
    {
        /** @var CatalogPromotionScope $constraint */
        Assert::isInstanceOf($constraint, CatalogPromotionScope::class);

        foreach ($configuration['variants'] as $variantCode) {
            if (null === $this->variantRepository->findOneBy(['code' => $variantCode])) {
                $context
                    ->buildViolation('sylius.catalog_promotion_scope.for_variants.invalid_variants')
                    ->atPath('configuration.variants')
                    ->addViolation()
                ;

                return;
            }
        }
    }
}

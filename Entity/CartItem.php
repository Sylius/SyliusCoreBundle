<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Entity;

use Sylius\Bundle\AssortmentBundle\Model\Variant\VariantInterface;
use Sylius\Bundle\CartBundle\Entity\CartItem as BaseCartItem;

/**
 * Cart item with the product variant attached.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CartItem extends BaseCartItem
{
    /**
     * Variant.
     *
     * @var VariantInterface
     */
    protected $variant;

    /**
     * Get variant.
     *
     * @return VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Set variant.
     *
     * @param VariantInterface $variant
     */
    public function setVariant(VariantInterface $variant)
    {
        $this->variant = $variant;

        return $this;
    }
}

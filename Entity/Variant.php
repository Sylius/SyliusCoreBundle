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

use Sylius\Bundle\AssortmentBundle\Entity\Variant\Variant as BaseVariant;
use Sylius\Bundle\AssortmentBundle\Model\Variant\VariantInterface;
use Sylius\Bundle\SalesBundle\Model\SellableInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxCategoryInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxableInterface;

/**
 * Sylius core product variant entity.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Variant extends BaseVariant implements SellableInterface
{
    /**
     * The variant price.
     *
     * @var float
     */
    protected $price;

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set variant price.
     *
     * @param float $price
     *
     * @return Variant
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults(VariantInterface $masterVariant)
    {
        parent::setDefaults($masterVariant);

        $this->setPrice($masterVariant->getPrice());
    }

    /**
     * {@inheritdoc}
     */
    public function getSellableName()
    {
        return $this->product->getName();
    }
}

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

use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Bundle\AssortmentBundle\Entity\Variant\Variant as BaseVariant;
use Sylius\Bundle\AssortmentBundle\Model\Variant\VariantInterface;
use Sylius\Bundle\SalesBundle\Model\SellableInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxCategoryInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxableInterface;
use Sylius\Bundle\InventoryBundle\Model\StockableInterface;

/**
 * Sylius core product variant entity.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Variant extends BaseVariant implements StockableInterface, SellableInterface
{
    /**
     * The variant price.
     *
     * @var float
     */
    protected $price;

    /**
     * On hand stock.
     *
     * @Assert\NotBlank
     * @Assert\Min(0)
     *
     * @var integer
     */
    protected $onHand;

    /**
     * Is variant available on demand?
     *
     * @var Boolean
     */
    protected $availableOnDemand;

    /**
     * Override constructor to set on hand stock.
     */
    public function __construct()
    {
        parent::__construct();

        $this->price = 0.00;
        $this->onHand = 1;
        $this->availableOnDemand = true;
    }

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
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isInStock()
    {
        return 0 < $this->onHand;
    }

    /**
     * {@inheritdoc}
     */
    public function getOnHand()
    {
        return $this->onHand;
    }

    /**
     * {@inheritdoc}
     */
    public function setOnHand($onHand)
    {
        $this->onHand = $onHand;

        if (0 > $this->onHand) {
            $this->onHand = 0;
        }
    }

    public function getInventoryName()
    {
        return $this->product->getName();
    }

    public function isAvailableOnDemand()
    {
        return $this->availableOnDemand;
    }

    public function setAvailableOnDemand($availableOnDemand)
    {
        $this->availableOnDemand = (Boolean) $availableOnDemand;
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

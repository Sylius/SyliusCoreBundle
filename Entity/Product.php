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

use Sylius\Bundle\AssortmentBundle\Entity\CustomizableProduct as BaseProduct;
use Sylius\Bundle\TaxationBundle\Model\TaxCategoryInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxableInterface;

/**
 * Sylius core product entity.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Product extends BaseProduct implements TaxableInterface
{
    /**
     * Short product description.
     * For lists displaying.
     *
     * @var string
     */
    protected $shortDescription;

    /**
     * Tax category.
     *
     * @var TaxCategoryInterface
     */
    protected $taxCategory;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setMasterVariant(new Variant());
    }

    /**
     * Gets product price.
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->getMasterVariant()->getPrice();
    }

    /**
     * Sets product price.
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->getMasterVariant()->setPrice($price);

        return $this;
    }

    /**
     * Get product short description.
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set product short description.
     *
     * @param string $shortDescription
     *
     * @return Product
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxCategory()
    {
        return $this->taxCategory;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxCategory(TaxCategoryInterface $category = null)
    {
        $this->taxCategory = $category;
    }
}

<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface;
use Sylius\Bundle\SandboxBundle\Entity\Product;

/**
 * Default assortment products to play with Sylius sandbox.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadProductsData extends DataFixture
{
    /**
     * Product property entity class.
     *
     * @var string
     */
    private $productPropertyClass;

    /**
     * Total variants created.
     *
     * @var integer
     */
    private $totalVariants;

    /**
     * SKU collection.
     *
     * @var array
     */
    private $skus;

    public function __construct()
    {
        parent::__construct();

        $this->skus = array();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->productPropertyClass = $this->container->getParameter('sylius.model.product_property.class');

        // T-Shirts...
        for ($i = 1; $i <= 120; $i++) {
            switch (rand(0, 3)) {
                case 0:
                    $manager->persist($this->createTShirt($i));
                break;

                case 1:
                    $manager->persist($this->createSticker($i));
                break;

                case 2:
                    $manager->persist($this->createMug($i));
                break;

                case 3:
                    $manager->persist($this->createBook($i));
                break;
            }

            if (0 === $i % 20) {
                $manager->flush();
            }
        }

        $manager->flush();

        // Define constant with number of total variants created.
        define('SYLIUS_ASSORTMENT_FIXTURES_TV', $this->totalVariants);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 6;
    }

    /**
     * Creates t-shirt product.
     *
     * @param integer $i
     */
    private function createTShirt($i)
    {
        $product = $this->createProduct();

        $product->setTaxCategory($this->getTaxCategory('Taxable goods'));
        $product->setName(sprintf('T-Shirt "%s" in different sizes and colors', $this->faker->word));
        $product->setDescription($this->faker->paragraph);

        $this->addMasterVariant($product);

        $this->setTaxons($product, array('T-Shirts', 'SuperTees'));

        // T-Shirt brand.
        $randomBrand = $this->faker->randomElement(array('Nike', 'Adidas', 'Puma', 'Potato'));
        $this->addProperty($product, 'Property.T-Shirt.Brand', $randomBrand);

        // T-Shirt collection.
        $randomCollection = sprintf('Symfony2 %s %s', $this->faker->randomElement(array('Summer', 'Winter', 'Spring', 'Autumn')), rand(1995, 2012));
        $this->addProperty($product, 'Property.T-Shirt.Collection', $randomCollection);

        // T-Shirt material.
        $randomMaterial = $this->faker->randomElement(array('Polyester', 'Wool', 'Polyester 10% / Wool 90%', 'Potato 100%'));
        $this->addProperty($product, 'Property.T-Shirt.Made-of', $randomMaterial);

        $product->addOption($this->getReference('Option.T-Shirt.Size'));
        $product->addOption($this->getReference('Option.T-Shirt.Color'));

        $this->generateVariants($product);

        $this->setReference('Product-'.$i, $product);

        return $product;
    }

    /**
     * Create sticker product.
     *
     * @param integer $i
     */
    private function createSticker($i)
    {
        $product = $this->createProduct();

        $product->setTaxCategory($this->getTaxCategory('Taxable goods'));
        $product->setName(sprintf('Great sticker "%s" in different sizes', $this->faker->word));
        $product->setDescription($this->faker->paragraph);

        $this->addMasterVariant($product);

        $this->setTaxons($product, array('Stickers', 'Stickypicky'));

        // Sticker resolution.
        $randomResolution = $this->faker->randomElement(array('Waka waka', 'FULL HD', '300DPI', '200DPI'));
        $this->addProperty($product, 'Property.Sticker.Resolution', $randomResolution);

        // Sticker paper.
        $randomPaper = sprintf('Paper from tree %s', $this->faker->randomElement(array('Wung', 'Yang', 'Lemon-San', 'Me-Gusta')));
        $this->addProperty($product, 'Property.Sticker.Paper', $randomPaper);

        $product->addOption($this->getReference('Option.Sticker.Size'));

        $this->generateVariants($product);

        $this->setReference('Product-'.$i, $product);

        return $product;
    }

    /**
     * Create mug product.
     *
     * @param integer $i
     */
    private function createMug($i)
    {
        $product = $this->createProduct();

        $product->setTaxCategory($this->getTaxCategory('Taxable goods'));
        $product->setName(sprintf('Mug "%s", many types available', $this->faker->word));
        $product->setDescription($this->faker->paragraph);

        $this->addMasterVariant($product);

        $this->setTaxons($product, array('Mugs', 'Mugland'));

        $randomMugMaterial = $this->faker->randomElement(array('Invisible porcelain', 'Banana skin', 'Porcelain', 'Sand'));
        $this->addProperty($product, 'Property.Mug.Material', $randomMugMaterial);

        $product->addOption($this->getReference('Option.Mug.Type'));

        $this->generateVariants($product);

        $this->setReference('Product-'.$i, $product);

        return $product;
    }

    /**
     * Create book product.
     *
     * @param integer $i
     */
    private function createBook($i)
    {
        $product = $this->createProduct();

        $author = $this->faker->name;
        $isbn = $this->getUniqueISBN();

        $product->setTaxCategory($this->getTaxCategory('Taxable goods'));
        $product->setName(sprintf('Book "%s" by "%s", product wihout options', ucfirst($this->faker->word), $author));
        $product->setDescription($this->faker->paragraph);

        $this->addMasterVariant($product, $isbn);

        $this->setTaxons($product, array('Books', 'Bookmania'));

        $this->addProperty($product, 'Property.Book.Author', $author);
        $this->addProperty($product, 'Property.Book.ISBN', $isbn);
        $this->addProperty($product, 'Property.Book.Pages', $this->faker->randomNumber(3));

        $this->setReference('Product-'.$i, $product);

        return $product;
    }

    /**
     * Generates all possible variants with random prices.
     *
     * @param CustomizableProductInterface $product
     */
    private function generateVariants(CustomizableProductInterface $product)
    {
        $this
            ->getVariantGenerator()
            ->generate($product)
        ;

        foreach ($product->getVariants() as $variant) {
            $variant->setAvailableOn($this->faker->dateTimeThisYear);
            $variant->setPrice($this->faker->randomNumber(5) / 100);
            $variant->setSku($this->getUniqueSku());
            $variant->setOnHand($this->faker->randomNumber(1));

            $this->setReference('Variant-'.$this->totalVariants, $variant);
            $this->totalVariants++;
        }
    }

    /**
     * Adds master variant to product.
     *
     * @param CustomizableProductInterface $product
     * @param string                       $sku
     */
    private function addMasterVariant(CustomizableProductInterface $product, $sku = null)
    {
        if (null === $sku) {
            $sku = $this->getUniqueSku();
        }

        $variant = $product->getMasterVariant();
        $variant->setProduct($product);
        $variant->setPrice($this->faker->randomNumber(5) / 100);
        $variant->setSku($sku);
        $variant->setAvailableOn($this->faker->dateTimeThisYear);
        $variant->setOnHand($this->faker->randomNumber(1));

        $this->setReference('Variant-'.$this->totalVariants, $variant);
        $this->totalVariants++;

        $product->setMasterVariant($variant);
    }

    /**
     * Adds property to product with given value.
     *
     * @param CustomizableProductInterface $product
     * @param string                       $propertyReference
     * @param string                       $value
     */
    private function addProperty(CustomizableProductInterface $product, $propertyReference, $value)
    {
        $property = new $this->productPropertyClass;
        $property->setProperty($this->getReference($propertyReference));
        $property->setProduct($product);
        $property->setValue($value);

        $product->addProperty($property);
    }

    private function setTaxons(CustomizableProductInterface $product, array $taxonNames)
    {
        return; // TODO: enable when we add taxonomy bundle

        $taxons = new ArrayCollection();

        foreach ($taxonNames as $taxonName) {
            $taxons->add($this->getReference('Taxon.'.$taxonName));
        }

        $product->setTaxons($taxons);
    }

    private function getTaxCategory($name)
    {
        return $this->getReference('Sylius.TaxCategory.'.ucfirst($name));
    }

    private function getUniqueSku($length = 5)
    {
        do {
            $sku = $this->faker->randomNumber($length);
        } while (in_array($sku, $this->skus));

        $this->skus[] = $sku;

        return $sku;
    }

    private function getUniqueISBN()
    {
        return $this->getUniqueSku(13);
    }

    private function createProduct()
    {
        return $this
            ->getProductRepository()
            ->createNew()
        ;
    }
}

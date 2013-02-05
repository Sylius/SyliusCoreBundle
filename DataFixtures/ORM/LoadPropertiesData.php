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

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Default assortment product properties to play with Sylius sandbox.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadPropertiesData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createProperty('T-Shirt brand', 'Brand', 'Property.T-Shirt.Brand'));
        $manager->persist($this->createProperty('T-Shirt collection', 'Collection', 'Property.T-Shirt.Collection'));
        $manager->persist($this->createProperty('T-Shirt material', 'Made of', 'Property.T-Shirt.Made-of'));

        $manager->persist($this->createProperty('Sticker print resolution', 'Print resolution', 'Property.Sticker.Resolution'));
        $manager->persist($this->createProperty('Sticker paper', 'Paper', 'Property.Sticker.Paper'));

        $manager->persist($this->createProperty('Mug material', 'Material', 'Property.Mug.Material'));

        $manager->persist($this->createProperty('Book author', 'Author', 'Property.Book.Author'));
        $manager->persist($this->createProperty('Book ISBN', 'ISBN', 'Property.Book.ISBN'));
        $manager->persist($this->createProperty('Book pages', 'Number of pages', 'Property.Book.Pages'));

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * Create property.
     *
     * @param string $name
     * @param string $presentation
     * @param string $reference
     */
    private function createProperty($name, $presentation, $reference)
    {
        $repository = $this->getPropertyRepository();

        $property = $repository->createNew();
        $property->setName($name);
        $property->setPresentation($presentation);

        $this->setReference($reference, $property);

        return $property;
    }
}

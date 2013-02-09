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

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base data fixture.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
abstract class DataFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * Container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Faker.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get country repository.
     *
     * @return ObjectRepository
     */
    protected function getCountryRepository()
    {
        return $this->get('sylius.repository.country');
    }

    /**
     * Get province repository.
     *
     * @return ObjectRepository
     */
    protected function getProvinceRepository()
    {
        return $this->get('sylius.repository.province');
    }

    /**
     * Get zone repository.
     *
     * @return ObjectRepository
     */
    protected function getZoneRepository()
    {
        return $this->get('sylius.repository.zone');
    }

    /**
     * Get zone member repository.
     *
     * @return ObjectRepository
     */
    protected function getZoneMemberRepository($zoneType)
    {
        return $this->get('sylius.repository.zone_member_'.$zoneType);
    }

    /**
     * Get shipping category repository.
     *
     * @return ObjectRepository
     */
    protected function getShippingCategoryRepository()
    {
        return $this->get('sylius.repository.shipping_category');
    }

    /**
     * Get shipping method repository.
     *
     * @return ObjectRepository
     */
    protected function getShippingMethodRepository()
    {
        return $this->get('sylius.repository.shipping_method');
    }

    /**
     * Get tax category repository.
     *
     * @return ObjectRepository
     */
    protected function getTaxCategoryRepository()
    {
        return $this->get('sylius.repository.tax_category');
    }

    /**
     * Get tax rate repository.
     *
     * @return ObjectRepository
     */
    protected function getTaxRateRepository()
    {
        return $this->get('sylius.repository.tax_rate');
    }

    /**
     * @return ObjectRepository
     */
    protected function getProductRepository()
    {
        return $this->get('sylius.repository.product');
    }

    /**
     * @return ObjectRepository
     */
    protected function getVariantRepository()
    {
        return $this->get('sylius.repository.variant');
    }

    /**
     * @return ObjectRepository
     */
    protected function getOptionRepository()
    {
        return $this->get('sylius.repository.option');
    }

    /**
     * @return ObjectRepository
     */
    protected function getOptionValueRepository()
    {
        return $this->get('sylius.repository.option_value');
    }

    /**
     * @return ObjectRepository
     */
    protected function getPropertyRepository()
    {
        return $this->get('sylius.repository.property');
    }

    /**
     * @return ObjectRepository
     */
    protected function getProductPropertyRepository()
    {
        return $this->get('sylius.repository.product_property');
    }

    /**
     * @return VariantGenerator
     */
    protected function getVariantGenerator()
    {
        return $this->get('sylius.variant_generator');
    }

    /**
     * Get zone reference by its name.
     *
     * @param string $name
     *
     * @return ZoneInterface
     */
    protected function getZoneByName($name)
    {
        return $this->getReference('Sylius.Zone.'.$name);
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }
}

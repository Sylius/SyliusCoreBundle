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

use Sylius\Bundle\ShippingBundle\Model\ShipmentInterface;
use Sylius\Bundle\ShippingBundle\Model\ShipmentItemInterface;
use Sylius\Bundle\ShippingBundle\Model\ShippableInterface;

/**
 * Custom inventory unit class.
 * Can be attached to order.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class InventoryUnit implements ShipmentItemInterface
{
    /**
     * Id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Shipping state.
     *
     * @var string ShipmentItemInterface::STATE_*
     */
    protected $shippingState;

    /**
     * Creation time.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Last update time.
     *
     * @var DateTime
     */
    protected $updatedAt;

    public function __construct()
    {
        parent::__construct();

        $this->shippingState = ShipmentItemInterface::STATE_READY;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getShipment()
    {
    }

    public function setShipment(ShipmentInterface $shipment = null)
    {
    }

    public function getShippable()
    {
    }

    public function setShippable(ShippableInterface $shippable)
    {
    }

    public function getShippingState()
    {
        return $this->shippingState;
    }

    public function setShippingState($state)
    {
        $this->shippingState = $state;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

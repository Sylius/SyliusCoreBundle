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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\UserInterface;
use Sylius\Bundle\AddressingBundle\Model\AddressInterface;
use Sylius\Bundle\InventoryBundle\Model\InventoryUnitInterface;
use Sylius\Bundle\SalesBundle\Entity\Order as BaseOrder;
use Sylius\Bundle\SalesBundle\Model\AdjustmentInterface;
use Sylius\Bundle\ShippingBundle\Model\ShipmentInterface;

/**
 * Order entity.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Order extends BaseOrder
{
    // Labels for tax and shipping adjustments.
    const TAX_ADJUSTMENT      = 'Tax';
    const SHIPPING_ADJUSTMENT = 'Shipping';

    /**
     * User.
     *
     * @var UserInterface
     */
     protected $user;

    /**
     * Order shipping address.
     *
     * @var AddressInterface
     */
    protected $shippingAddress;

    /**
     * Order billing address.
     *
     * @var AddressInterface
     */
    protected $billingAddress;

    /**
     * Shipments for this order.
     *
     * @var Collection
     */
    protected $shipments;

    /**
     * Inventory units.
     *
     * @var Collection
     */
    protected $inventoryUnits;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->inventoryUnits = new ArrayCollection();
        $this->shipments = new ArrayCollection();
    }

    /**
     * Get user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(AddressInterface $address)
    {
        $this->shippingAddress = $address;
    }

    /**
     * {@inheritdoc}
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddress(AddressInterface $address)
    {
        $this->billingAddress = $address;
    }

    /**
     * Get the tax total.
     *
     * @return float
     */
    public function getTaxTotal()
    {
        $taxTotal = 0;

        foreach ($this->getTaxAdjustments() as $adjustment) {
            $taxTotal += $adjustment->getAmount();
        }

        return $taxTotal;
    }

    /**
     * Get all tax adjustments.
     *
     * @return Collection
     */
    public function getTaxAdjustments()
    {
        return $this->adjustments->filter(function (AdjustmentInterface $adjustment) {
            return Order::TAX_ADJUSTMENT === $adjustment->getLabel();
        });
    }

    /**
     * Remove all tax adjustments.
     */
    public function removeTaxAdjustments()
    {
        foreach ($this->getTaxAdjustments() as $adjustment) {
            $this->removeAdjustment($adjustment);
        }

        return $this;
    }

    /**
     * Get shipping total.
     *
     * @return float
     */
    public function getShippingTotal()
    {
        $shippingTotal = 0;

        foreach ($this->getShippingAdjustments() as $adjustment) {
            $shippingTotal += $adjustment->getAmount();
        }

        return $shippingTotal;
    }

    /**
     * Get all shipping adjustments.
     *
     * @return Collection
     */
    public function getShippingAdjustments()
    {
        return $this->adjustments->filter(function (AdjustmentInterface $adjustment) {
            return Order::SHIPPING_ADJUSTMENT === $adjustment->getLabel();
        });
    }

    /**
     * Remove all shipping adjustments.
     */
    public function removeShippingAdjustments()
    {
        foreach ($this->getShippingAdjustments() as $adjustment) {
            $this->removeAdjustment($adjustment);
        }

        return $this;
    }

    /**
     * Get all inventory units.
     *
     * @return Collection
     */
    public function getInventoryUnits()
    {
        return $this->inventoryUnits;
    }

    /**
     * Add inventory unit.
     *
     * @param InventoryUnitInterface $unit
     */
    public function addInventoryUnit(InventoryUnitInterface $unit)
    {
        if (!$this->inventoryUnits->contains($unit)) {
            $unit->setOrder($this);
            $this->inventoryUnits->add($unit);
        }

        return $this;
    }

    /**
     * Remove inventory unit.
     *
     * @param InventoryUnitInterface $unit
     */
    public function removeInventoryUnit(InventoryUnitInterface $unit)
    {
        if ($this->inventoryUnits->contains($unit)) {
            $unit->setOrder(null);
            $this->inventoryUnits->removeElement($unit);
        }

        return $this;
    }

    /**
     * Get all shipments associated with this order.
     *
     * @return Collection
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * Add a shipment.
     *
     * @param ShipmentInterface $shipment
     */
    public function addShipment(ShipmentInterface $shipment)
    {
        if (!$this->hasShipment($shipment)) {
            $shipment->setOrder($this);
            $this->shipments->add($shipment);
        }
    }

    /**
     * Remove shipment.
     *
     * @param ShipmentInterface $shipment
     */
    public function removeShipment(ShipmentInterface $shipment)
    {
        if ($this->hasShipment($shipment)) {
            $shipment->setOrder(null);
            $this->shipments->removeElement($shipment);
        }
    }

    /**
     * Has shipment?
     *
     * @param ShipmentInterface $shipment
     *
     * @return Boolean
     */
    public function hasShipment(ShipmentInterface $shipment)
    {
        return $this->shipments->contains($shipment);
    }
}

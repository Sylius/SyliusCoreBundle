<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\Entity;

use PHPSpec2\ObjectBehavior;
use Sylius\Bundle\ShippingBundle\Model\ShipmentItemInterface;

class InventoryUnit extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Entity\InventoryUnit');
    }

    function it_should_not_belong_to_an_order_by_default()
    {
        $this->getOrder()->shouldReturn(null);
    }

    /**
     * @param Sylius\Bundle\SalesBundle\Model\OrderInterface $order
     */
    function it_should_allow_attaching_itself_to_an_order($order)
    {
        $this->setOrder($order);
        $this->getOrder()->shouldReturn($order);
    }

    /**
     * @param Sylius\Bundle\SalesBundle\Model\OrderInterface $order
     */
    function it_should_allow_detaching_itself_from_an_order($order)
    {
        $this->setOrder($order);
        $this->getOrder()->shouldReturn($order);

        $this->setOrder(null);
        $this->getOrder()->shouldReturn(null);
    }

    function it_should_implement_Sylius_shipment_item_interface()
    {
        $this->shouldImplement('Sylius\Bundle\ShippingBundle\Model\ShipmentItemInterface');
    }

    function it_should_not_belong_to_any_shipment_by_default()
    {
        $this->getShipment()->shouldReturn(null);
    }

    /**
     * @param Sylius\Bundle\ShippingBundle\Model\ShipmentInterface $shipment
     */
    function it_should_allow_assigning_itself_to_shipment($shipment)
    {
        $this->setShipment($shipment);
        $this->getShipment()->shouldReturn($shipment);
    }

    /**
     * @param Sylius\Bundle\ShippingBundle\Model\ShipmentInterface $shipment
     */
    function it_should_allow_detaching_itself_from_shipment($shipment)
    {
        $this->setShipment($shipment);
        $this->getShipment()->shouldReturn($shipment);

        $this->setShipment(null);
        $this->getShipment()->shouldReturn(null);
    }

    function it_should_have_ready_shipping_state_by_default()
    {
        $this->getShippingState()->shouldReturn(ShipmentItemInterface::STATE_READY);
    }

    function its_shipping_state_should_be_mutable()
    {
        $this->setShippingState(ShipmentItemInterface::STATE_SHIPPED);
        $this->getShippingState()->shouldReturn(ShipmentItemInterface::STATE_SHIPPED);
    }
}

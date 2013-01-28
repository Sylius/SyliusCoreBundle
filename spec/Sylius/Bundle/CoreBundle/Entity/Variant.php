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

class Variant extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Entity\Variant');
    }

    function it_should_not_have_price_by_default()
    {
        $this->getPrice()->shouldReturn(null);
    }

    function its_price_should_be_mutable()
    {
        $this->setPrice(4.99);
        $this->getPrice()->shouldReturn(4.99);
    }

    /**
     * @param Sylius\Bundle\AssortmentBundle\Model\Variant\VariantInterface $masterVariant
     */
    function it_should_inherit_price_from_master_variant($masterVariant)
    {
        $masterVariant->isMaster()->willReturn(true);
        $masterVariant->getAvailableOn()->willReturn(new \DateTime('yesterday'));
        $masterVariant->getPrice()->willReturn(4.99);

        $this->setDefaults($masterVariant);

        $this->getPrice()->shouldReturn(4.99);
    }
}

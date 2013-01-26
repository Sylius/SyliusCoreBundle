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

class Product extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Entity\Product');
    }

    function it_should_implement_Sylius_customizable_product_interface()
    {
        $this->shouldImplement('Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface');
    }

    function it_should_extend_Sylius_customizable_product_entity()
    {
        $this->shouldHaveType('Sylius\Bundle\AssortmentBundle\Entity\CustomizableProduct');
    }

    function it_should_not_have_short_description_by_default()
    {
        $this->getShortDescription()->shouldReturn(null);
    }

    function its_short_description_should_be_mutable()
    {
        $this->setShortDescription('Amazing product...');
        $this->getShortDescription()->shouldReturn('Amazing product...');
    }
}

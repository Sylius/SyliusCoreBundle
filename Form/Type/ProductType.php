<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\AssortmentBundle\Form\Type\CustomizableProductType as BaseProductType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Product form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductType extends BaseProductType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('shortDescription', 'textarea', array(
                'required' => false,
                'label'    => 'sylius.label.product.short_description'
            ))
            ->add('taxCategory', 'sylius_taxation_category_choice', array(
                'required'    => false,
                'empty_value' => '---',
                'label'       => 'sylius.label.product.tax_category'
            ))
        ;
    }
}

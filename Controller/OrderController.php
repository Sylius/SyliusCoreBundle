<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends ResourceController
{
    /**
     * Render order filter form.
     */
    public function filterFormAction()
    {
        return $this->renderResponse('SyliusWebBundle:Backend/Order:filterForm.html', array(
            'form' => $this->get('form.factory')->createNamed('criteria', 'sylius_order_filter')->createView()
        ));
    }
}

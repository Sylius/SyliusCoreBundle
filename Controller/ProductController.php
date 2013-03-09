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

/**
 * Product controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductController extends ResourceController
{
    /**
     * List products categorized under given taxon.
     *
     * @param Request $request
     * @param string  $permalink
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $config = $this->getConfiguration();

        $taxon = $this
            ->getTaxonController()
            ->findOr404(array('permalink' => $permalink))
        ;

        $paginator = $this
            ->getRepository()
            ->createByTaxonPaginator($taxon)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        $products = $paginator->getCurrentPageResults();

        return $this->renderResponse('indexByTaxon.html', array(
            'taxon'     => $taxon,
            'products'  => $products,
            'paginator' => $paginator
        ));
    }

    /**
     * Get taxon controller.
     *
     * @return ResourceController
     */
    private function getTaxonController()
    {
        return $this->get('sylius.controller.taxon');
    }
}

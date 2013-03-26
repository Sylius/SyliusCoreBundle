<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Repository;

use Sylius\Bundle\AssortmentBundle\Entity\CustomizableProductRepository;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;

/**
 * Product repository.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductRepository extends CustomizableProductRepository
{
    /**
     * Create paginator for products categorized
     * under given taxon.
     *
     * @param TaxonInterface
     *
     * @return PagerfantaInterface
     */
    public function createByTaxonPaginator(TaxonInterface $taxon)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('product.taxons', 'taxon')
            ->andWhere('taxon = :taxon')
            ->setParameter('taxon', $taxon)
        ;

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Find X recently added products.
     *
     * @param integer $limit
     *
     * @return ProductInterface[]
     */
    public function findLatest($limit = 10)
    {
        return $this->findBy(array(), array('createdAt' => 'desc'), $limit);
    }
}

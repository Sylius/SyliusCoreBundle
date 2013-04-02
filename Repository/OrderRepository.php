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

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use YaLinqo\Enumerable;
use DateTime;

class OrderRepository extends EntityRepository
{
    public function getTotalStatistics()
    {
        return Enumerable::from($this->findBetweenDates(new DateTime('1 year ago'), new DateTime()))
            ->groupBy(function($order) {
                return $order->getCreatedAt()->format('m');
            }, '$v->getTotal()', function($orders) {
                return Enumerable::from($orders)->sum();
            })
            ->toValues()
            ->toArray()
        ;
    }

    public function getCountStatistics()
    {
        return Enumerable::from($this->findBetweenDates(new DateTime('1 year ago'), new DateTime()))
            ->groupBy(function($order) {
                return $order->getCreatedAt()->format('m');
            }, null, function($orders) {
                return Enumerable::from($orders)->count();
            })
            ->toValues()
            ->toArray()
        ;
    }

    public function findBetweenDates($from, $to)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->gte('o.createdAt', ':from'))
            ->andWhere($queryBuilder->expr()->lte('o.createdAt', ':to'))
            ->setParameter('from', new DateTime('1 year ago'))
            ->setParameter('to', new DateTime())
            ->getQuery()
            ->getResult()
        ;
    }
}

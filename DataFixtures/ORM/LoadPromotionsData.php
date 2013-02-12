<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\PromotionsBundle\Model\RuleInterface;
use Sylius\Bundle\PromotionsBundle\Model\ActionInterface;

/**
 * Default promotion fixtures.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class LoadPromotionsData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
        $promotion = $this->createPromotion(
            'New Year',
            'New Year Sale for 10 and more items',
            array(
                $this->createRule(RuleInterface::TYPE_ITEM_COUNT, array('count' => 10, 'equal' => true)),
            ),
            array(
                $this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, array('count' => 20)),
            )
        );
        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'Christmas',
            'Christmas Sale for orders over 5000 EUR',
            array(
                $this->createRule(RuleInterface::TYPE_ORDER_TOTAL, array('amount' => 5000, 'equal' => true)),
            ),
            array(
                $this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, array('count' => 200)),
            )
        );
        $manager->persist($promotion);

        $manager->flush();
    }

    private function createRule($type, array $configuration)
    {
        $rule = $this
            ->getPromotionRuleRepository()
            ->createNew()
        ;

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }

    private function createAction($type, array $configuration)
    {
        $action = $this
            ->getPromotionActionRepository()
            ->createNew()
        ;

        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }

    private function createPromotion($name, $description, array $rules, array $actions)
    {
        $promotion = $this
            ->getPromotionRepository()
            ->createNew()
        ;

        $promotion->setName($name);
        $promotion->setDescription($description);

        foreach ($rules as $rule) {
            $promotion->addRule($rule);
        }
        foreach ($actions as $action) {
            $promotion->addAction($action);
        }

        $this->setReference('Sylius.Promotion.'.$name, $promotion);

        return $promotion;
    }

    public function getOrder()
    {
        return 1;
    }
}

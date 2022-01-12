<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\CoreBundle\Calculator\DelayStampCalculatorInterface;
use Sylius\Component\Core\Model\CatalogPromotionInterface;
use Sylius\Component\Promotion\Event\CatalogPromotionCreated;
use Sylius\Component\Promotion\Event\CatalogPromotionEnded;
use Sylius\Component\Promotion\Event\CatalogPromotionUpdated;
use Sylius\Component\Promotion\Provider\DateTimeProviderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class CatalogPromotionEventListener
{
    private MessageBusInterface $eventBus;

    private DelayStampCalculatorInterface $delayStampCalculator;

    private DateTimeProviderInterface $dateTimeProvider;

    public function __construct(
        MessageBusInterface $eventBus,
        DelayStampCalculatorInterface $delayStampCalculator,
        DateTimeProviderInterface $dateTimeProvider
    ) {
        $this->eventBus = $eventBus;
        $this->delayStampCalculator = $delayStampCalculator;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function dispatchCatalogPromotionCreatedEvent(GenericEvent $event): void
    {
        /** @var CatalogPromotionInterface $catalogPromotion */
        $catalogPromotion = $event->getSubject();
        Assert::isInstanceOf($catalogPromotion, CatalogPromotionInterface::class);

        $this->eventBus->dispatch(
            new CatalogPromotionCreated($catalogPromotion->getCode()),
            $this->calculateStartDateStamp($catalogPromotion)
        );

        $this->dispatchCatalogPromotionEndedEvent($catalogPromotion);
    }

    public function dispatchCatalogPromotionUpdatedEvent(GenericEvent $event): void
    {
        /** @var CatalogPromotionInterface $catalogPromotion */
        $catalogPromotion = $event->getSubject();
        Assert::isInstanceOf($catalogPromotion, CatalogPromotionInterface::class);

        $this->eventBus->dispatch(
            new CatalogPromotionUpdated($catalogPromotion->getCode()),
            $this->calculateStartDateStamp($catalogPromotion)
        );

        $this->dispatchCatalogPromotionEndedEvent($catalogPromotion);
    }

    private function calculateStartDateStamp(CatalogPromotionInterface $catalogPromotion): array
    {
        if ($catalogPromotion->getStartDate() !== null) {
            return [$this->delayStampCalculator->calculate($this->dateTimeProvider->now(), $catalogPromotion->getStartDate())];
        }

        return [];
    }

    private function dispatchCatalogPromotionEndedEvent(CatalogPromotionInterface $catalogPromotion): void
    {
        if ($catalogPromotion->getEndDate() !== null) {
            $this->eventBus->dispatch(
                new CatalogPromotionEnded($catalogPromotion->getCode()),
                [$this->delayStampCalculator->calculate($this->dateTimeProvider->now(), $catalogPromotion->getEndDate())]
            );
        }
    }
}

<?php

namespace src;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Session\SessionComponent;
use Hk\Core\CoreComponent;
use Hk\Web\Core\Subscribers\MatchedRouteSubscriber;

class WebComponent extends BaseComponent implements EventSubscriberAwareInterface, DependsOnAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEventSubscribers(): array
    {
        return [
            EventDispatcherInterface::class => [MatchedRouteSubscriber::class],
        ];
    }

    public function dependsOn(): array
    {
        return [
            CoreComponent::class,
            SessionComponent::class
        ];
    }
}
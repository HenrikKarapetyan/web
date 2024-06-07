<?php

namespace Henrik\Web;

use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Core\CoreComponent;
use Henrik\Route\RouteComponent;
use Henrik\Web\Subscribers\MatchedRouteSubscriber;

class WebComponent extends CoreComponent implements EventSubscriberAwareInterface, DependsOnAwareInterface
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
            RouteComponent::class,
        ];
    }
}
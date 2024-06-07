<?php

namespace Henrik\Web;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Route\RouteComponent;
use Henrik\Web\Subscribers\MatchedRouteSubscriber;
use Hk\Core\CoreComponent;

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
            RouteComponent::class,
        ];
    }
}
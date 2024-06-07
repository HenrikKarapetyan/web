<?php

namespace Henrik\Web;

use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\OnBootstrapAwareInterface;
use Henrik\Contracts\CoreEvents;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Core\CoreComponent;
use Henrik\Route\RouteComponent;
use Henrik\Web\Subscribers\MatchedRouteSubscriber;

class WebComponent extends CoreComponent implements DependsOnAwareInterface, OnBootstrapAwareInterface
{
    public function dependsOn(): array
    {
        return [
            CoreComponent::class,
            RouteComponent::class,
        ];
    }

    public function onBootstrapDispatchEvents(): array
    {
        return [
            EventDispatcherInterface::class => [MatchedRouteSubscriber::class => CoreEvents::HTTP_REQUEST_EVENTS],
        ];
    }
}
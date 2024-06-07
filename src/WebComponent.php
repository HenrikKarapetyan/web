<?php

namespace Henrik\Web;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\ComponentInterfaces\OnBootstrapAwareInterface;
use Henrik\Contracts\CoreEvents;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Contracts\Http\RequestInterface;
use Henrik\Core\CoreComponent;
use Henrik\Route\RouteComponent;
use Henrik\Web\Subscribers\MatchedRouteSubscriber;
use Henrik\Web\Subscribers\RequestHandlerSubscriber;

class WebComponent extends BaseComponent implements EventSubscriberAwareInterface, DependsOnAwareInterface, OnBootstrapAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEventSubscribers(): array
    {
        return [
            EventDispatcherInterface::class                    => [MatchedRouteSubscriber::class],
            CoreEvents::ROUTE_DISPATCHER_DEFAULT_DEFINITION_ID => [RequestHandlerSubscriber::class],

        ];
    }

    public function getServices(): array
    {
        return require 'config/services.php';
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrapDispatchEvents(): array
    {
        return [
            CoreEvents::ROUTE_DISPATCHER_DEFAULT_DEFINITION_ID => [
                RequestInterface::class => CoreEvents::HTTP_REQUEST_EVENTS,
            ],
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
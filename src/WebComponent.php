<?php

namespace Henrik\Web;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\ComponentInterfaces\DependsOnAwareInterface;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\ComponentInterfaces\OnBootstrapAwareInterface;
use Henrik\Contracts\CoreEvents;
use Henrik\Contracts\Enums\ServiceScope;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Contracts\Http\RequestInterface;
use Henrik\Contracts\Utils\MarkersInterface;
use Henrik\Core\CoreComponent;
use Henrik\Events\EventDispatcher;
use Henrik\Http\Request;
use Henrik\Route\RouteComponent;
use Henrik\View\Extension\AssetExtension;
use Henrik\View\Renderer;
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
        return [
            ServiceScope::SINGLETON->value => [
                [
                    'id'    => AssetExtension::class,
                    'class' => AssetExtension::class,
                    'args'  => [
                        'basePath' => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'assetsDir',
                    ],
                ],
                [
                    'id'     => Renderer::class,
                    'class'  => Renderer::class,
                    'params' => [
                        'viewDirectory' => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'viewDirectory',
                    ],
                ],
                [
                    'id'    => MatchedRouteSubscriber::class,
                    'class' => MatchedRouteSubscriber::class,
                ],
                [
                    'id'    => CoreEvents::ROUTE_DISPATCHER_DEFAULT_DEFINITION_ID,
                    'class' => EventDispatcher::class,
                ],
            ],

            ServiceScope::PARAM->value => [
                RequestInterface::class => Request::createFromGlobals(),
            ],
        ];
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
<?php

namespace Henrik\Web\Subscribers;

use Henrik\Contracts\CoreEvents;
use Henrik\Contracts\EventDispatcherInterface;
use Henrik\Contracts\EventSubscriberInterface;
use Henrik\Contracts\Http\RequestInterface;
use Henrik\Route\Interfaces\RouteDispatcherInterface;

readonly class RequestHandlerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RouteDispatcherInterface $routeDispatcher,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CoreEvents::HTTP_REQUEST_EVENTS => 'handleServerRequest',
        ];
    }

    /**
     * @param RequestInterface $request
     */
    public function handleServerRequest(RequestInterface $request): void
    {

        $routeData = $this->routeDispatcher->dispatch(
            $request->getRequestUri(),
            $request->getMethod()
        );

        $this->eventDispatcher->dispatch($routeData, CoreEvents::ROUTE_MATCH_EVENTS);

    }
}
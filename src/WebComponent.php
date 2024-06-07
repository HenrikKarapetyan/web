<?php

namespace src;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\ComponentInterfaces\EventSubscriberAwareInterface;
use Henrik\Contracts\EventDispatcherInterface;
use Hk\Web\Core\Subscribers\MatchedRouteSubscriber;

class WebComponent extends BaseComponent implements EventSubscriberAwareInterface
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
}
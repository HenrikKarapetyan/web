<?php

declare(strict_types=1);

namespace Hk\Web\Core\Subscribers;

use Henrik\Contracts\CoreEvents;
use Henrik\Contracts\DependencyInjectorInterface;
use Henrik\Contracts\EventSubscriberInterface;
use Henrik\Contracts\FunctionInvokerInterface;
use Henrik\Contracts\HandlerTypesEnum;
use Henrik\Contracts\Http\ResponseInterface;
use Henrik\Contracts\MethodInvokerInterface;
use Henrik\Contracts\Route\RouteInterface;
use Henrik\Contracts\Utils\MarkersInterface;

readonly class MatchedRouteSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private FunctionInvokerInterface $functionInvoker,
        private MethodInvokerInterface $methodInvoker,
        private DependencyInjectorInterface $dependencyInjector,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CoreEvents::ROUTE_MATCH_EVENTS => 'onMatchedRoute',
        ];
    }

    public function onMatchedRoute(RouteInterface $routeData): void
    {
        $routeOptions = $routeData->getRouteOptions();

        $handler = $routeOptions->getHandler();

        $methodOrFunctionResponse = null;

        switch ($routeOptions->getHandlerType()) {
            case HandlerTypesEnum::FUNCTION:
                $methodOrFunctionResponse = $this->functionInvoker->invoke($handler, $routeData->getParams()); // @phpstan-ignore-line

                break;

            case HandlerTypesEnum::METHOD:
                /** @var string $handler */
                $methodOrFunctionResponse = $this->resolveMethodCall($handler, $routeData);
        }

        if ($methodOrFunctionResponse instanceof ResponseInterface) {
            $methodOrFunctionResponse->send();
        }
    }

    private function resolveMethodCall(string $handler, RouteInterface $routeData): mixed
    {

        $handlerArray = explode(MarkersInterface::AS_SERVICE_PARAM_MARKER, $handler);

        /** @var object $controller */
        $controller = $this->dependencyInjector->get($handlerArray[0]);

        if (is_callable($controller) && !isset($handlerArray[1])) {
            //            return $controller($routeData->getParams());
            return $this->methodInvoker->invoke($controller, '__invoke', $routeData->getParams());
        }

        return $this->methodInvoker->invoke($controller, $handlerArray[1], $routeData->getParams());
    }
}
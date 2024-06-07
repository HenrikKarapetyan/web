<?php

use Henrik\Contracts\Enums\ServiceScope;
use Henrik\Contracts\Http\RequestInterface;
use Henrik\Contracts\Utils\MarkersInterface;

use Henrik\Http\Request;
use Henrik\View\Extension\AssetExtension;
use Henrik\View\Renderer;
use Henrik\Web\Subscribers\MatchedRouteSubscriber;

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
    ],

    ServiceScope::PARAM->value => [
        RequestInterface::class => Request::createFromGlobals(),
    ],
];
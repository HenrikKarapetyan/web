<?php

namespace Henrik\Web;

use Henrik\Contracts\DependencyInjectorInterface;
use Henrik\Http\Response;
use Henrik\View\Extension\AssetExtension;
use Henrik\View\Renderer;
use Throwable;

abstract readonly class AbstractController
{
    public function __construct(
        private Renderer $renderer,
        protected DependencyInjectorInterface $dependencyInjector,
        private AssetExtension $assetExtension
    ) {}

    /**
     * @param string               $template
     * @param array<string, mixed> $params
     *
     * @throws Throwable
     *
     * @return Response
     */
    public function asView(string $template, array $params = []): Response
    {
        $this->renderer->addExtension($this->assetExtension);

        return (new Response())->withAddedHeaders([
            'Content-Type' => 'text/html',
        ])
            ->withContent((string) $this->renderer->render($template, $params));
    }

    /**
     * @param mixed $data
     * @param int   $statusCode
     *
     * @return Response
     */
    public function asJson(mixed $data, int $statusCode = 200): Response
    {
        return (new Response())->withAddedHeaders([
            'Content-Type' => 'application/json',
        ])
            ->withStatusCode($statusCode)
            ->withContent((string) json_encode($data));
    }
}
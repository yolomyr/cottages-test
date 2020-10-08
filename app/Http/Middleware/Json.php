<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class Json
{
    /**
     * The Response Factory our app uses
     *
     * @var ResponseFactory
     */
    protected $factory;

    /**
     * JsonMiddleware constructor.
     *
     * @param ResponseFactory $factory
     */
    public function __construct(ResponseFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    final public function handle(Request $request, Closure $next): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            $response = $this->factory->json(
                $response->content(),
                $response->status(),
                $response->headers->all()
            );
        }

        return $response;
    }
}

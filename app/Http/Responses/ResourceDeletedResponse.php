<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class ResourceDeletedResponse implements Responsable
{
    public function toResponse($request): \Illuminate\Http\Response|Application|Response|ResponseFactory
    {
        return response(null, 204);
    }
}

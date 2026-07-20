<?php

namespace App\Http\Middleware;

use App\Services\ProducerProvisioner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProducerProvisioned
{
    public function __construct(private readonly ProducerProvisioner $provisioner) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $this->provisioner->provision($request->user());
        }

        return $next($request);
    }
}

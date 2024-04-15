<?php

namespace xGrz\PayU\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayUWhitelist
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // hack if you are under cloudflare protection to get real request->ip()
        $clientIpAddress = $request->headers->get('cf-connecting-ip') ?? $request->ip();

        if (!in_array($clientIpAddress, [
            // sandbox
            '185.68.14.10', '185.68.14.11', '185.68.14.12',
            '185.68.14.26', '185.68.14.27', '185.68.14.28',

            // prod
            '185.68.12.10', '185.68.12.11', '185.68.12.12',
            '185.68.12.26', '185.68.12.27', '185.68.12.28'
        ])) return response('Forbidden.', 403);

        return $next($request);
    }
}

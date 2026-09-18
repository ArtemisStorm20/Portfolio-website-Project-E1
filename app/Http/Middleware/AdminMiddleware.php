<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //Een ingelogde gebruiker is niet automatisch een beheerder. Daarom wordt gecontroleerd of de gebruiker is ingelogd én of deze de juiste beheerdersrechten heeft.
        abort_unless($request->user()?->is_admin, 403);

        return $next($request);
    }
}

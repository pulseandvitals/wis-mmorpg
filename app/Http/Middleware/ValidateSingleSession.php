<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateSingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
       if (auth()->check()) {

            $user = auth()->user();
            $current = session()->getId();

            // ALWAYS refresh session on first valid request
            if (!$user->session_id) {
                $user->session_id = $current;
                $user->save();
            }

            // mismatch = kick
            if ($user->session_id !== $current) {

                auth()->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login');
            }
        }

        return $next($request);
    }
}

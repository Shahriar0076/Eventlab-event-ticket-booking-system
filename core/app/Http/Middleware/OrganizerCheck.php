<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class OrganizerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('organizer')->check()) {
            $organizer = authOrganizer();
            if ($organizer->status  && $organizer->ev  && $organizer->sv  && $organizer->tv) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'  => 'unverified',
                        'status'  => 'error',
                        'message' => ['error' => $notify],
                        'data' => [
                            'user' => $organizer
                        ],
                    ]);
                } else {
                    return to_route('organizer.authorization');
                }
            }
        }
        abort(403);
    }
}

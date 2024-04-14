<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var User $user
         */
        $user = auth('api')->user();
        if (!$user->isTeacher()) {
            return response()->json(["message" => __('alert.forbidden')], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}

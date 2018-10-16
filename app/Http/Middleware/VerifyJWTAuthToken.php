<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class VerifyJWTAuthToken
{
    const TOKEN_NAME = 'auth-token';

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$request->ajax() && !$request->session()->has(self::TOKEN_NAME)) {
                throw new JWTException("Haven't token in session.");
            }

            $authToken = $request->session()->get(self::TOKEN_NAME);
            $this->jwtAuth->setToken($authToken);
            \Log::debug('Validate token for dashboard requests.');

            $this->jwtAuth->checkOrFail(); // check if token is valid
            \Log::debug('Token is valid and access is allowed.');
            return $next($request);
        } catch (JWTException $e) {
            \Log::warning('User is not allowed for dashboard. Exception: ' . $e->getMessage());

            if ($request->session()->has(self::TOKEN_NAME)) {
                $request->session()->forget(self::TOKEN_NAME);
            }

            return redirect('/');
        }
    }
}

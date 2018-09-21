<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Dingo\Api\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Login user in system.
     *
     * @param LoginUserRequest $request user credentials
     *
     * @return Response
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // TODO localize error message
                return Response::create(
                    ['errors' => ['User credentials are not correct!']], Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (JWTException $e) {
            // TODO localize error message
            return Response::create(
                ['errors' => ['Something went wrong!']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return Response::create(['token' => $token]);
    }

    /**
     * Logout user from system.
     *
     * @return Response
     */
    public function logout()
    {
        $token = JWTAuth::getToken();

        try {
            if (!JWTAuth::invalidate($token)) {
                // TODO localize error message
                return Response::create(
                    ['errors' => ['Your token was corrupted.']], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            // TODO localize error message
            return Response::create(
                ['errors' => ['Something went wrong!']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return Response::create(['message' => 'User is logged off.']);
    }
}

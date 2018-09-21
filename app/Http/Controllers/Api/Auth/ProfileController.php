<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    /**
     * Get information about user.
     *
     * @return Response
     */
    public function __invoke()
    {
        try {
            if (!$user = JWTAuth::parseToken()->toUser()) {
                return Response::create(
                    ['errors' => ['User not found!']], Response::HTTP_NOT_FOUND);
            }
        } catch (JWTException $e) {
            return Response::create(
                ['errors' => ['Something went wrong!']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return Response::create($user);
    }
}

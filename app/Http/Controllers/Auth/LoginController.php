<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyJWTAuthToken;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws \RuntimeException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginPost(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (\Auth::validate($credentials)) {
            $user = User::whereEmail($credentials['email'])->first();

            if ($user !== null) {
                if (!$token = JWTAuth::fromUser($user)) {
                    abort(401);
                }

                $request->session()->put(VerifyJWTAuthToken::TOKEN_NAME, $token);
                return redirect()->route('dashboard');
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Prolong current session.
     *
     * @param Request                $request
     * @param \Tymon\JWTAuth\JWTAuth $jwtAuth
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \RuntimeException
     */
    public function prolongSession(Request $request, \Tymon\JWTAuth\JWTAuth $jwtAuth)
    {
        try {
            $expiredToken = $request->input('token');
            $jwtAuth->setToken($expiredToken);
            $token = $jwtAuth->refresh();

            $request->session()->put(VerifyJWTAuthToken::TOKEN_NAME, $token);

            return response()
                ->json([
                    'success'        => true,
                    'message'        => trans('messages.prolong.success'),
                    'refreshedToken' => $token,
                ]);
        } catch (JWTException $e) {
            \Log::alert('Prolong session: ' . $e->getMessage());
            return response()
                ->json(['success' => false, 'message' => trans('messages.token.mismatch')], 301);
        }
    }

    /**
     * Logout user from system.
     *
     * @param Request                $request
     * @param \Tymon\JWTAuth\JWTAuth $jwtAuth
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPost(Request $request, \Tymon\JWTAuth\JWTAuth $jwtAuth)
    {
        try {
            \Log::debug('Start logout process.');
            session()->forget(VerifyJWTAuthToken::TOKEN_NAME);
            session()->regenerate();

            \Log::debug('User session is regenerated.');

            $token = $request->input('token');
            $jwtAuth->setToken($token);
            $jwtAuth->invalidate();

            \Log::info('User successfully logged out');

            return response()->json(['success' => true, 'message' => trans('messages.logout.success')]);
        } catch (JWTException $e) {
            \Log::alert('Logout: ' . $e->getMessage());

            session()->forget(VerifyJWTAuthToken::TOKEN_NAME);
            session()->regenerate();

            return response()
                ->json(['success' => false, 'message' => trans('messages.token.mismatch')], 301);
        }
    }
}

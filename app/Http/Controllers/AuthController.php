<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller {
	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth:api', ['except' => ['login']]);
	}

	/**
	 * Get a JWT token via given credentials.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request) {
		$credentials = $request->only('email', 'password');
		if ($token = $this->guard()->attempt($credentials)) {
			return $this->respondWithToken($token);
		}
		return response()->json(['error' => 'Invalid Login Details'], 401);
	}

	/**
	 * Get the authenticated User.
	 
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me() {
		return response()->json($this->guard()->user(), 200);
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout() {
		$this->guard()->logout();
		return response()->json(['message' => 'Successfully logged out'], 200);
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh() {
		
		return $this->respondWithToken($this->guard()->refresh());
		
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function jwtRefresh($token) {
		
		return $this->respondWithToken($token);
		
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token) {
		return response()->json([
			'access_token' 	=> $token,
			'token_type' 	=> 'bearer',
			'refresh_token' => '',
			'expires_in'    => $this->guard()->factory()->getTTL()*60,
			'user'			=> $this->me()->original
		]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\Guard
	 */
	public function guard() {
		return Auth::guard('api');
	}


	// public function Tes() {
	// 	return Http::timeout(env('API_TIMEOUT', '10000'))->post('localhost:8080/api/tes/satu');
	// }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserLogin;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Get a JWT via given credentials",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Users email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Users password"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Return token object { 'access_token', 'token_type', 'expires_in' }"
     *     )
     * )
     * @param UserLogin $userLogin
     * @return JsonResponse
     */
    final public function login(UserLogin $userLogin): JsonResponse
    {
        $credentials = $userLogin->validated();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/me",
     *     operationId="me",
     *     tags={"Auth"},
     *     summary="Get the authenticated User",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Returns user data"
     *     ),
     * )
     * @return JsonResponse
     */
    final public function me(): JsonResponse
    {
        $user = auth()->user();
        $user->gender;
        $user->user_estates;
        return response()->json($user);
    }


    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     summary="Log the user out (Invalidate the token)",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @return JsonResponse
     */
    final public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     operationId="refresh",
     *     tags={"Auth"},
     *     summary="Refresh a token",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Return token object { 'access_token', 'token_type', 'expires_in' }"
     *     )
     * )
     * @return JsonResponse
     */
    final public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

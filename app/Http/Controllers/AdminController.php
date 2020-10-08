<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserVerification;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin:api');
    }

    /**
     * @OA\Post(
     *     path="/api/admin/verify/user",
     *     operationId="verify",
     *     tags={"Admin"},
     *     summary="Verify user account. Accessable only by administator users",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"user_id"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description=""
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param UserVerification $userVerification
     * @return JsonResponse
     */
    final public function verifyUser(UserVerification $userVerification): JsonResponse {
        $validated = $userVerification->validated();
        $user = User::findOrFail($validated['user_id']);
        if (empty($user->user_verified_at)) {
            $user->verify();
        } else {
            response()->json([
                'message' => 'User already verified'
            ]);
        }

        return response()->json([
            'message' => 'User verified'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/booking/all/get",
     *     operationId="getAllBookings",
     *     tags={"Admin"},
     *     summary="Get all users bookings",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="service_id",
     *                     type="integer",
     *                     description=""
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description=""
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    final public function getAllBookings(Request $request): JsonResponse {
        $request->validate([
            'user_id' => 'integer|exists:users,id',
            'service_id' => 'integer|exists:services,id'
        ]);

        $user_id = (int) $request->post('user_id');
        $service_id = (int) $request->post('service_id');

        return response()->json( Booking::getBookings($service_id, $user_id) );
    }
}

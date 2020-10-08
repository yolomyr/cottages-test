<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmail;
use App\Http\Requests\ChangePassword;
use App\Http\Requests\ChangeProfile;
use App\Mail\UserChangeEmail;
use App\Mail\UserChangePassword;
use App\Models\EmailReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    /**
     * Create a new ProfileController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change/password",
     *     operationId="changePassword",
     *     tags={"User"},
     *     summary="Change auth users password",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"password", "new_password", "new_password_confirmation"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Current users password"
     *                 ),
     *                 @OA\Property(
     *                     property="new_password",
     *                     type="string",
     *                     description="New users password"
     *                 ),
     *                 @OA\Property(
     *                     property="new_password_confirmation",
     *                     type="string",
     *                     description="New users password confirmation"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param ChangePassword $changePasswordRequest
     * @return JsonResponse
     */
    final public function changePassword(ChangePassword $changePasswordRequest): JsonResponse {
        $validated = $changePasswordRequest->validated();

        $user = auth()->user();
        $user->setPassword($validated['new_password']);

        Mail::to($user->email)->send(new UserChangePassword());

        return response()->json([
            'message' => 'Password changed'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change/email",
     *     operationId="changeEmail",
     *     tags={"User"},
     *     summary="Change auth users email",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"email", "new_email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Current users email"
     *                 ),
     *                 @OA\Property(
     *                     property="new_email",
     *                     type="string",
     *                     description="New users email"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param ChangeEmail $changeEmailRequest
     * @return JsonResponse
     */
    final public function changeEmail(ChangeEmail $changeEmailRequest): JsonResponse {
        $validated = $changeEmailRequest->validated();
        $user = auth()->user();
        $token = EmailReset::addToken($user->id, $validated['new_email']);
        Mail::to($user->email)->send(new UserChangeEmail($user, $validated['new_email'], $token));

        return response()->json([
            'message' => 'Email change confirmation sent to email'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change",
     *     operationId="changeProfile",
     *     tags={"User"},
     *     summary="Change auth users profile data",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Users name. Size should be less than 100 characters"
     *                 ),
     *                 @OA\Property(
     *                     property="surname",
     *                     type="string",
     *                     description="Users surname. Size should be less than 100 characters"
     *                 ),
     *                 @OA\Property(
     *                     property="birthday",
     *                     type="string",
     *                     format="date",
     *                     description="Users birthday date"
     *                 ),
     *                 @OA\Property(
     *                     property="gender_id",
     *                     type="integer",
     *                     enum={1, 2},
     *                     default=1,
     *                     description="User gender_id, 1 = Male, 2 = Female"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Users phone. Unique value, should be masked value in format +7 (111) 222-33-44, server proceed to format +71112223344 to save in DB and returns to client in masked format"
     *                 ),
     *                 @OA\Property(
     *                     property="whatsapp_phone",
     *                     type="string",
     *                     description="Should be masked value in format +7 (111) 222-33-44"
     *                 ),
     *                 @OA\Property(
     *                     property="telegram_phone",
     *                     type="string",
     *                     description="Should be masked value in format +7 (111) 222-33-44"
     *                 ),
     *                 @OA\Property(
     *                     property="viber_phone",
     *                     type="string",
     *                     description="Should be masked value in format +7 (111) 222-33-44"
     *                 ),
     *                 @OA\Property(
     *                     property="work_info",
     *                     type="string",
     *                     description="Size should be less than 1000 characters. Can be empty"
     *                 ),
     *                 @OA\Property(
     *                     property="hobby_info",
     *                     type="string",
     *                     description="Size should be less than 1000 characters. Can be empty"
     *                 ),
     *                 @OA\Property(
     *                     property="family_info",
     *                     type="string",
     *                     description="Size should be less than 1000 characters. Can be empty"
     *                 ),
     *                 @OA\Property(
     *                     property="extra_info",
     *                     type="string",
     *                     description="Size should be less than 1000 characters. Can be empty"
     *                 ),
     *                 @OA\Property(
     *                     property="user_estates",
     *                     type="string",
     *                     format="array",
     *                     description="User estates data. estate_type_id variations is { 1: 'Участок', 2: 'Квартира'}. estate_number should be greater or equal 0. estate_number required when picked estate_type_id. If you want to update estate you should pass 'id' parameter",
     *                     example="{ 'user_estates': [ { 'id': 1, 'estate_type_id': 1, estate_number: 10 } ] }"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param ChangeProfile $changeProfileRequest
     * @return JsonResponse
     */
    final public function change(ChangeProfile $changeProfileRequest): JsonResponse {
        auth()->user()->setProfile( $changeProfileRequest->validated() );
        return response()->json([
            'message' => 'Profile saved'
        ]);
    }
}

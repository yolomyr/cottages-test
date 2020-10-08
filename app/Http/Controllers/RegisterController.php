<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegister;
use App\Mail\Admin\UserRegistered;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserConfirmation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Register"},
     *     summary="Register user",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "surname", "birthday", "gender_id", "phone"},
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
     *                     property="email",
     *                     type="string",
     *                     description="Users email. Unique value"
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
     *                     format="json",
     *                     description="User estates data. estate_type_id variations is { 1: 'Участок', 2: 'Квартира'}. estate_number should be greater or equal 0. estate_number required when picked estate_type_id",
     *                     example="{ 'user_estates': [ { 'estate_type_id': 1, estate_number: 10 } ] }"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @param UserRegister $userRequest
     * @return JsonResponse
     */
    final public function register(UserRegister $userRequest): JsonResponse {
        $user_data = $userRequest->validated();
        $user = User::createWithRole($user_data);
        $admin_emails = User::getAdminEmails();

        $token = UserConfirmation::addToken($user->id);

        if (!empty($admin_emails)) {
            Mail::to($admin_emails)->send(new UserRegistered($user, $token));
        }

        return response()->json([
            'message' => 'User registered'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/password/reset/send",
     *     operationId="passwordResetNotification",
     *     tags={"Register"},
     *     summary="Reset user password by email. Token from link expires in 1 hour",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Users email. Unique value"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="If the user is found, the email will be sent"
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    final public function passwordResetNotification(Request $request): JsonResponse {
        $request->validate([
            'email' => 'required|email'
        ]);

        PasswordReset::notify( $request->post('email') );

        return response()->json([
            'message' => 'If the user is found, the email will be sent'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     operationId="passwordReset",
     *     tags={"Register"},
     *     summary="Reset user password by email",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"password", "password_confirmation"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Users new password. Minimum 8 symbols"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     description="Users new password cpnfirmation. Minimum 8 symbols"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Users verification token from email. Token expires in 1 hour"
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
    final public function passwordReset(Request $request): JsonResponse {
        $request->validate([
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $token = $request->post('token');

        $password_reset = PasswordReset::where('token', $token)->whereRaw('created_at > date_sub(NOW(), interval 1 hour)')->first();

        if (empty($password_reset)) {
            return response()->json([
                'message' => 'Token expired'
            ]);
        }

        $user = User::where('email', $password_reset->email)->firstOrFail();
        $user->setPassword($request->post('password'));
        $password_reset->delete();

        return response()->json([
            'message' => 'Password changed'
        ]);
    }
}

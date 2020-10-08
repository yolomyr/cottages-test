<?php

namespace App\Http\Controllers;

use App\Models\EmailReset;
use App\Models\UserConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * * @OA\Get(
     *     path="/api/verify/user",
     *     operationId="verify",
     *     tags={"Verification"},
     *     summary="Verify user account. Accessable by link",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Verify user id"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Verification token expires after 1 day"
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
     * @return RedirectResponse
     */
    final public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'token' => 'required|string'
        ]);

        $user_id = $request->get('id');
        $token = $request->get('token');

        UserConfirmation::confirm($user_id, $token);

        return redirect()->away(env('APP_URL'));
    }

    /**
     * * @OA\Get(
     *     path="/api/change/email",
     *     operationId="confirmEmailChange",
     *     tags={"Verification"},
     *     summary="Change user email. Accessable by link",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Email change user id"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Email change token expires after 1 day"
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
     * @return RedirectResponse
     */
    final public function confirmEmailChange(Request $request): RedirectResponse {
        $request->validate([
            'id' => 'required|integer',
            'token' => 'required|string'
        ]);

        $user_id = $request->get('id');
        $token = $request->get('token');

        EmailReset::reset($user_id, $token);

        return redirect()->away(env('APP_URL') . '/login/success');
    }

    /**
     * * @OA\Get(
     *     path="/api/approve/booking",
     *     operationId="approveBooking",
     *     tags={"Verification"},
     *     summary="Approve booking shceduler route",
     *     description="",
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @return RedirectResponse
     */
    final public function approveBooking(): RedirectResponse {
        \Artisan::call('booking:approve');
        return redirect()->away(env('APP_URL'));
    }
}

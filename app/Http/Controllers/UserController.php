<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\UserEstateType;
use App\Traits\JoinRequest;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/directory/user/joinable",
     *     operationId="joinModels",
     *     tags={"Directory"},
     *     summary="Get all related to user directories tables data",
     *     description="To add related tables you should change $joinable_models property in UserController",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    use JoinRequest;

    public array $joinable_models = [
        'user_estate_types' => UserEstateType::class,
        'genders' => Gender::class
    ];
}

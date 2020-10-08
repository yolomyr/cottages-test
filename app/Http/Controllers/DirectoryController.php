<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingleDirectory;
use App\Interfaces\iDirectory;
use Illuminate\Http\JsonResponse;
use Throwable;

class DirectoryController extends Controller implements iDirectory
{
    /**
     * @OA\Post(
     *     path="/api/directory/single",
     *     operationId="getSingle",
     *     tags={"Directory"},
     *     summary="Gets single directory (related table | catalog table)",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"mode", "company_id"},
     *                 @OA\Property(
     *                     property="directory_name",
     *                     type="string",
     *                     enum={"user_estate_types", "genders"},
     *                     default="user_estate_types",
     *                     description="Directory table name"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @param SingleDirectory $directory
     * @return JsonResponse
     */
    final public function getSingle(SingleDirectory $directory): JsonResponse {
        $validated = $directory->validated();
        $directory = $validated['directory_name'];

        if (!$directory) {
            return response()->json(['error' => 'Not found directory'], 404);
        }

        try {
            $directory_class = self::DIRECTORIES[$directory];
            return response()->json($directory_class::all());
        } catch (Throwable $e) {
            report($e);

            return response()->json(['error' => 'Not found directory'], 404);
        }
    }
}

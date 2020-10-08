<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateService;
use App\Http\Requests\UpdateService;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct() {
        $this->middleware('admin:api')->only(['create', 'update']);
    }

    /**
     * @OA\Post(
     *     path="/api/service",
     *     operationId="index",
     *     tags={"Services"},
     *     summary="Get all services posts",
     *     description="",
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @return JsonResponse
     */
    final public function index(): JsonResponse {
        $services = Service::all();
        foreach ($services as $service) {
            $service->status;
        }

        return response()->json([
            'services' => $services
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/service/single",
     *     operationId="single",
     *     tags={"Services"},
     *     summary="Get single service post data",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"service_id"},
     *                 @OA\Property(
     *                     property="service_id",
     *                     type="integer",
     *                     description="Service id"
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
    final public function single(Request $request): JsonResponse {
        $request->validate([
            'service_id' => 'required|exists:services,id'
        ]);

        $service = Service::find( $request->post('service_id') );
        $service->status;

        return response()->json([
            'service' => $service
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/service/create",
     *     operationId="create",
     *     tags={"Services"},
     *     summary="Create service post",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "logo"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Service title"
     *                 ),
     *                 @OA\Property(
     *                     property="status_id",
     *                     type="integer",
     *                     description="Service status. Variations: 1 - active, 2 - inactive. Default value is 1"
     *                 ),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="Service logo image"
     *                 ),
     *                 @OA\Property(
     *                     property="unavailability_reason",
     *                     type="string",
     *                     description="Service booking unavailability reason"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param CreateService $createService
     * @return JsonResponse
     */
    final public function create(CreateService $createService): JsonResponse {
        $validated = $createService->validated();

        Service::createService($validated);

        return response()->json([
            'message' => 'Service created'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/service/update",
     *     operationId="update",
     *     tags={"Services"},
     *     summary="Update service post",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Service id"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Service title"
     *                 ),
     *                 @OA\Property(
     *                     property="status_id",
     *                     type="integer",
     *                     description="Service status. Variations: 1 - active, 2 - inactive. Default value is 1"
     *                 ),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="Service logo image"
     *                 ),
     *                 @OA\Property(
     *                     property="unavailability_reason",
     *                     type="string",
     *                     description="Service booking unavailability reason"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param UpdateService $updateService
     * @return JsonResponse
     */
    final public function update(UpdateService $updateService): JsonResponse {
        $validated = $updateService->validated();

        $service = Service::findOrFail($validated['id'])->fill($validated);

        if ((int) $validated['status_id'] === ServiceStatus::STATUSES['inactive'] && $service->isDirty('status_id')) {
            Booking::cancelBookings($service);
        }

        $service->updateService();

        return response()->json([
            'message' => 'Service updated'
        ]);
    }
}

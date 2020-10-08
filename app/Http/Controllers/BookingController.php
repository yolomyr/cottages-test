<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\DeleteBookingRequest;
use App\Models\Booking;
use App\Models\BookingType;
use App\Models\ServiceBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Post(
     *     path="/api/booking",
     *     operationId="index",
     *     tags={"Booking"},
     *     summary="Get all booking info",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"service_id", "booking_type_id"},
     *                 @OA\Property(
     *                     property="service_id",
     *                     type="integer",
     *                     description="Current service id. Available services - 1,Бассейн; 2,Тенистый корт; 3,Бильярд; 4,Кафе"
     *                 ),
     *                 @OA\Property(
     *                     property="booking_type_id",
     *                     type="integer",
     *                     description="Booking type id, Available booking types - 1,Свободная; 2,Семейная"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param Request $bookingRequest
     * @return JsonResponse
     */
    final public function index(Request $bookingRequest): JsonResponse {
        $bookingRequest->validate([
            'service_id' => 'required|integer|exists:services,id',
            'booking_type_id' => 'required|integer|exists:booking_types,id'
        ]);

        $service_id = $bookingRequest->post('service_id');
        $booking_type_id = $bookingRequest->post('booking_type_id');

        $bookings = Booking::getAllBookings($service_id, $booking_type_id);
        return response()->json($bookings + [
            'booking_types' => BookingType::all()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/booking/create",
     *     operationId="create",
     *     tags={"Booking"},
     *     summary="Get all booking info",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"service_id", "booking_type_id", "booking_date", "started_at", "finished_at"},
     *                 @OA\Property(
     *                     property="service_id",
     *                     type="integer",
     *                     description="Current service id. Available services - 1,Бассейн; 2,Тенистый корт; 3,Бильярд; 4,Кафе"
     *                 ),
     *                 @OA\Property(
     *                     property="booking_type_id",
     *                     type="integer",
     *                     description="Booking type id, Available booking types - 1,Свободная; 2,Семейная"
     *                 ),
     *                 @OA\Property(
     *                     property="booking_date",
     *                     format="date",
     *                     type="string",
     *                     example="2020-09-29",
     *                     description="Booking date"
     *                 ),
     *                 @OA\Property(
     *                     property="started_at",
     *                     format="time",
     *                     type="string",
     *                     example="17:50",
     *                     description="Booking start time"
     *                 ),
     *                 @OA\Property(
     *                     property="finished_at",
     *                     format="time",
     *                     type="string",
     *                     example="17:50",
     *                     description="Booking end time"
     *                 ),
     *                 @OA\Property(
     *                     property="people_number",
     *                     type="integer",
     *                     description="People number"
     *                 ),
     *                 @OA\Property(
     *                     property="commentary",
     *                     type="string",
     *                     description="Client's commentary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param CreateBookingRequest $bookingRequest
     * @return JsonResponse
     */
    final public function create(CreateBookingRequest $bookingRequest): JsonResponse {
        $validated = $bookingRequest->validated();

        Booking::createPost($validated);

        return response()->json( Booking::getAllBookings($validated['service_id'], $validated['booking_type_id']) );
    }

    /**
     * @OA\Post(
     *     path="/api/booking/delete",
     *     operationId="delete",
     *     tags={"Booking"},
     *     summary="Delete booking",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"booking_id"},
     *                 @OA\Property(
     *                     property="booking_id",
     *                     type="integer",
     *                     description="Current booking id"
     *                 ),
     *                 @OA\Property(
     *                     property="cancel_reason",
     *                     type="string",
     *                     description="Booking cancel reason"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param DeleteBookingRequest $bookingRequest
     * @return JsonResponse
     */
    final public function delete(DeleteBookingRequest $bookingRequest): JsonResponse {
        $validated = $bookingRequest->validated();

        $booking = Booking::find($validated['booking_id']);
        $booking_service = ServiceBooking::find($booking->service_booking_id);
        $booking->cancel($validated);

        return response()->json( Booking::getAllBookings($booking_service->service_id, $booking_service->booking_type_id) );
    }

    /**
     * @OA\Post(
     *     path="/api/booking/user/get",
     *     operationId="getUsersBookings",
     *     tags={"Booking"},
     *     summary="Get users service bookings",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="service_id",
     *                     type="integer",
     *                     description="Current service id"
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
    final public function getUsersBookings(Request $request): JsonResponse {
        $request->validate([
            'service_id' => 'integer|exists:services,id'
        ]);

        $user = auth()->user();
        $service_id = (int) $request->post('service_id');

        return response()->json( Booking::getBookings($service_id, $user->id) );
    }
}

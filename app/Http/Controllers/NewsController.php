<?php

namespace App\Http\Controllers;

use App\Helpers\Uploader;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Models\News;
use App\Models\NewsAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct() {
        $this->middleware('admin:api')->except([
            'index',
            'single'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news",
     *     operationId="index",
     *     tags={"News"},
     *     summary="Get news posts",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="limit",
     *                     type="integer",
     *                     description="News limit. Default value 8"
     *                 ),
     *                 @OA\Property(
     *                     property="offset",
     *                     type="integer",
     *                     description="News offset. Default value 0"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Return token object { 'access_token', 'token_type', 'expires_in' }"
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse {
        $request->validate([
            'limit' => 'integer',
            'offset' => 'integer|min:0'
        ]);

        $limit = $request->post('limit');
        $offset = $request->post('offset');

        return response()->json(News::get($offset, $limit));
    }

    /**
     * @OA\Post(
     *     path="/api/news/single",
     *     operationId="single",
     *     tags={"News"},
     *     summary="Get single news post",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"news_id"},
     *                 @OA\Property(
     *                     property="news_id",
     *                     type="integer",
     *                     description="News id"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Return token object { 'access_token', 'token_type', 'expires_in' }"
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    final public function single(Request $request): JsonResponse {
        $request->validate([
            'news_id' => 'required|exists:news,id'
        ]);

        $news = News::findOrFail( $request->post('news_id') );
        $news->attachments;

        return response()->json($news);
    }

    /**
     * @OA\Post(
     *     path="/api/news/create",
     *     operationId="create",
     *     tags={"News"},
     *     summary="Create news post",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"logo", "subtitle", "content"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="News logo image"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitle",
     *                     type="string",
     *                     description="News subtitle"
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     description="News content"
     *                 ),
     *                 @OA\Property(
     *                    property="files",
     *                    type="array",
     *                    description="News attachments",
     *                    @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                    )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Return token object { 'access_token', 'token_type', 'expires_in' }"
     *     )
     * )
     * @param NewsCreateRequest $createRequest
     * @return JsonResponse
     */
    final public function create(NewsCreateRequest $createRequest): JsonResponse {
        $validated = $createRequest->validated();

        News::add($validated);

        return response()->json([
            'message' => 'News created'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news/update",
     *     operationId="update",
     *     tags={"News"},
     *     summary="Update news post",
     *     description="files paramerer only creates new files. If files not changed, u should not post this parameter",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="News id"
     *                 ),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="News logo image"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitle",
     *                     type="string",
     *                     description="News subtitle"
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     description="News content"
     *                 ),
     *                 @OA\Property(
     *                     property="deleted_files[]",
     *                     type="integer",
     *                     description="Attachments ids array to delete files with posts"
     *                 ),
     *                 @OA\Property(
     *                    property="files",
     *                    type="array",
     *                    description="News attachments",
     *                    @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                    )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     )
     * )
     * @param NewsUpdateRequest $updateRequest
     * @return JsonResponse
     */
    final public function update(NewsUpdateRequest $updateRequest): JsonResponse {
        $validated = $updateRequest->validated();

        News::findOrFail($validated['id'])->updatePost($validated);

        return response()->json([
            'message' => 'News updated'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news/delete",
     *     operationId="delete",
     *     tags={"News"},
     *     summary="Delete news post with attachments",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"news_id"},
     *                 @OA\Property(
     *                     property="news_id",
     *                     type="integer",
     *                     description="News id"
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
    final public function delete(Request $request): JsonResponse {
        $request->validate([
            'news_id' => 'required|exists:news,id'
        ]);

        $news_id = $request->post('news_id');
        $news = News::findOrFail($news_id);
        $news->deletePostWithFiles();

        return response()->json([
            'message' => 'News deleted'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news/delete/file",
     *     operationId="deleteFile",
     *     tags={"News"},
     *     summary="Delete news post attachment",
     *     description="",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"attachment_id"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="attachment_id",
     *                     type="integer",
     *                     description="Attachment id"
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
    final public function deleteFile(Request $request): JsonResponse {
        $request->validate([
            'attachment_id' => 'required|exists:news_attachments,id'
        ]);

        $attachment_id = $request->post('attachment_id');
        NewsAttachment::findOrFail($attachment_id)->deletePostWithFile();

        return response()->json([
            'message' => 'News file deleted'
        ]);
    }
}

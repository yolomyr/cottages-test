<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Cottage Village API Documentation",
     *      description="",
     *      @OA\Contact(
     *          email="afonico@info.ru"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Tag(
     *     name="Projects",
     *     description="API Endpoints of Projects"
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

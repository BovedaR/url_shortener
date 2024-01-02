<?php

namespace App\Http\Controllers;

use App\Http\Services\UrlService;
use App\Models\Url;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="Urls",
 * description="API Endpoints of Urls Controller"
 * )
 */
class UrlController extends Controller
{
    protected $urlService;

    public function __construct()
    {
        $this->urlService = new UrlService($this->getCurrentUser());
    }

    /**
     * Display a listing of the url.
     */

         /**
     * @OA\Get(
     * path="/api/urls",
     * summary="Display a listing of the Urls",
     * tags={"Urls"},
     * @OA\Response(
     * response=200,
     * description="A listing of the url",
     * @OA\JsonContent(
     * @OA\Property(property="urls", type="object"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function index()
    {
        $urls = $this->urlService->getAllUrls();
        return response()->json($urls, $response['status'] ?? 200);
    }

    /**
     * Store a newly created url in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/urls",
     *     summary="Store a newly created url in storage",
     *     tags={"Urls"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass url data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="original_url", type="string"),
     *             @OA\Property(property="collection_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="url created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="url", type="object", ref="#/components/schemas/Url"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data passed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $response = $this->urlService->createUrl($request);
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Display the specified url.
     */

    /**
     * @OA\Get(
     * path="/api/urls/{url}",
     * summary="Display the specified url",
     * tags={"Urls"},
     * @OA\Response(
     * response=200,
     * description="Display the specified url",
     * @OA\JsonContent(
     * @OA\Property(property="url", type="object"),
     * ),
     * 
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="url not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * 
     * ),
     * ),
     * 
     */
    public function show(Url $url)
    {
        $response = $this->urlService->getUrlById($url);
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Update the specified url in storage.
     */

     /**
     * @OA\Put(
     *     path="/api/urls/{url}",
     *     summary="Update the specified url in storage",
     *     tags={"Urls"},
     *     @OA\Parameter(
     *         name="url",
     *         required=true,
     *         in="path",
     *         description="url ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass url data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="original_url", type="string"),
     *             @OA\Property(property="collection_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="url updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="url", type="object", ref="#/components/schemas/Url"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="url not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data passed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */

      
    public function update(Request $request, Url $url)
    {
        $response = $this->urlService->updateUrl($request, $url);
        return response()->json($response,$response['status'] ?? 200);
    }

    /**
     * Remove the specified url from storage.
     */

        /**
     * @OA\Delete(
     * path="/api/urls/{url}",
     * summary="Remove the specified url from storage",
     * tags={"Urls"},
     * @OA\Response(
     * response=200,
     * description="url deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="url", type="object"),
     * ),
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="url not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * 
     * ),
     * ),
     * 
     */
    public function destroy(Url $url)
    {
        $response = $this->urlService->deleteUrl($url);

        return response()->json($response, $response['status'] ?? 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Services\UrlCollectionService;
use App\Models\UrlCollection;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="UrlCollections",
 * description="API Endpoints of UrlCollections Controller"
 * )
 */
class UrlCollectionController extends Controller
{
    protected $urlCollectionService;

    public function __construct()
    {
        $this->urlCollectionService = new UrlCollectionService($this->getCurrentUser());
    }

    /**
     * Display a listing of the urlCollections.
     */

    /**
     * @OA\Get(
     * path="/api/urlCollections",
     * summary="Display a listing of the urlCollections",
     * tags={"UrlCollections"},
     * @OA\Response(
     * response=200,
     * description="A listing of the urlCollections",
     * @OA\JsonContent(
     * @OA\Property(property="urlCollections", type="object", ref="#/components/schemas/UrlCollection"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    

    public function index()
    {
        $response = $this->urlCollectionService->getAllUrlCollections();
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Store a newly created urlCollection in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/urlCollections",
     *     summary="Store a newly created urlCollection in storage",
     *     tags={"UrlCollections"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass urlCollection data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="UrlCollection created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="urlCollection", type="object", ref="#/components/schemas/UrlCollection"),
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
        $response = $this->urlCollectionService->createUrlCollection($request);
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Display the specified urlCollection.
     */

    /**
     * @OA\Get(
     *     path="/api/urlCollections/{urlCollection}",
     *     summary="Display the specified urlCollection",
     *     tags={"UrlCollections"},
     *     @OA\Parameter(
     *         name="urlCollection",
     *         in="path",
     *         description="UrlCollection ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="UrlCollection details",
     *         @OA\JsonContent(
     *             @OA\Property(property="urlCollection", type="object", ref="#/components/schemas/UrlCollection"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="UrlCollection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    public function show(UrlCollection $urlCollection)
    {
        $response = $this->urlCollectionService->getUrlCollectionById($urlCollection);
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Update the specified urlCollection in storage.
     */

   /**
     * @OA\Put(
     *     path="/api/urlCollections/{urlCollection}",
     *     summary="Update the specified urlCollection in storage",
     *     tags={"UrlCollections"},
     *     @OA\Parameter(
     *         name="urlCollection",
     *         in="path",
     *         description="UrlCollection ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass urlCollection data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="UrlCollection updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="urlCollection", type="object", ref="#/components/schemas/UrlCollection"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="UrlCollection not found",
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

    public function update(Request $request, UrlCollection $urlCollection)
    {
        $response = $this->urlCollectionService->updateUrlCollection($request, $urlCollection);
        return response()->json($response, $response['status'] ?? 200);
    }

    /**
     * Remove the specified urlCollection from storage.
     */

     /**
     * @OA\Delete(
     *     path="/api/urlCollections/{urlCollection}",
     *     summary="Remove the specified urlCollection from storage",
     *     tags={"UrlCollections"},
     *     @OA\Parameter(
     *         name="urlCollection",
     *         in="path",
     *         description="UrlCollection ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="UrlCollection deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="urlCollection", type="object", ref="#/components/schemas/UrlCollection"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="UrlCollection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UrlCollection has movies or tv shows",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    public function destroy(UrlCollection $urlCollection)
    {
        $response = $this->urlCollectionService->deleteUrlCollection($urlCollection);
        return response()->json($response, $response['status'] ?? 200);
    }
}

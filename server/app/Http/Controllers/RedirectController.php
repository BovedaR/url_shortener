<?php

namespace App\Http\Controllers;

use App\Http\Services\RedirectService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="Redirects",
 * description="API Endpoints of Redirect Controller"
 * )
 */
class RedirectController
{
    protected $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Redirect to the original URL with a specified collection.
     *
     * @OA\Get(
     *     path="/{collectionId}/{shortUrl}",
     *     summary="Redirect with collection",
     *     tags={"Redirects"},
     *     @OA\Parameter(
     *         name="collectionId",
     *         in="path",
     *         required=true,
     *         description="ID of the collection",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         name="shortUrl",
     *         in="path",
     *         required=true,
     *         description="Short URL hash",
     *         @OA\Schema(type="string", minLength=6, maxLength=6),
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to the original URL",
     *         @OA\Header(
     *             header="Location",
     *             description="Redirect location",
     *             @OA\Schema(type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    public function redirectWithCollection($collection, $shortUrl)
    {
        $url = $this->redirectService->findUrlByCollectionAndShortUrl($collection, $shortUrl);

        if ($url) {
            return redirect($url->original_url);
        }

        return response()->json("Not found", 404);
    }
    /**
     * Redirect to the original URL without a specified collection.
     *
     * @OA\Get(
     *     path="/{shortUrl}",
     *     summary="Redirect without collection",
     *     tags={"Redirects"},
     *     @OA\Parameter(
     *         name="shortUrl",
     *         in="path",
     *         required=true,
     *         description="Short URL hash",
     *         @OA\Schema(type="string", minLength=6, maxLength=6),
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to the original URL",
     *         @OA\Header(
     *             header="Location",
     *             description="Redirect location",
     *             @OA\Schema(type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function redirectWithoutCollection($shortUrl)
    {
        $url = $this->redirectService->findUrlByShortUrl($shortUrl);
        if ($url) {
            return redirect($url->original_url);
        }

        return response()->json("Not found", 404);
    }
}
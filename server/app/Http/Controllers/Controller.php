<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;

/** 
 * @OA\Info(
 *     version="1.0.0",
 *    title="Url Shortener API Documentation",
 *   description="This is a sample API documentation for the Url Shortener API",
 * 
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $currentUser;

    public function __construct(){
        $this->middleware(function ($request, $next){
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof TokenInvalidException){
                    return response()->json(['status' => 'Token is Invalid']);
                }else if ($e instanceof TokenExpiredException){
                    return response()->json(['status' => 'Token is Expired']);
                }else{
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }
            return $next($request);
        });
    }

    public function getCurrentUser(){
        $this->currentUser = JWTAuth::parseToken()->authenticate();
        return $this->currentUser;
    }
}

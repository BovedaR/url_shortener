<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="UrlCollection",
 *     description="UrlCollection model",
 * )
 *
 * @OA\Property(
 *     property="id",
 *     type="integer",
 *     description="UrlCollection id",
 *     example="1"
 * )
 * @OA\Property(
 *     property="name",
 *     type="string",
 *     description="UrlCollection name",
 *     example="My Collection"
 * )
 * @OA\Property(
 *     property="urls",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Url")
 * )
 * @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *     description="UrlCollection created at",
 *     example="2021-01-01 00:00:00"
 * )
 * @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *     description="UrlCollection updated at",
 *     example="2021-01-01 00:00:00"
 * )
 * @OA\Property(
 *     property="deleted_at",
 *     type="string",
 *     format="date-time",
 *     description="UrlCollection deleted at",
 *     example="2021-01-01 00:00:00"
 * )
 * @OA\Property(
 *     property="transformFull",
 *     type="object",
 *     ref="#/components/schemas/UrlCollection"
 * )
 * @OA\Property(
 *     property="transformSimple",
 *     type="object",
 *     ref="#/components/schemas/UrlCollection"
 * )
 */

class UrlCollection extends Model
{
    use HasFactory;

    protected $with = ['urls'];

    public function urls(){
        return $this->hasMany(Url::class, 'collection_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}

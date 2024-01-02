<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Url",
 *     description="Url model",
 * )
 *
 * @OA\Property(
 *     property="id",
 *     type="integer",
 *     description="Url id",
 *     example="1"
 * )
 * @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Url name",
 *     example="My Url"
 * )
 * @OA\Property(
 *     property="original_url",
 *     type="string",
 *     description="Original URL",
 *     example="https://example.com"
 * )
 * @OA\Property(
 *     property="short_url",
 *     type="string",
 *     description="Shortened URL",
 *     example="abc123"
 * )
 * @OA\Property(
 *     property="collection_id",
 *     type="object",
 *     ref="#/components/schemas/UrlCollection"
 * )
 */

class Url extends Model
{
    use HasFactory;

    public function collection(){
        return $this->belongsTo(UrlCollection::class, 'collection_id', 'id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'originalUrl' => $this->original_url,
            'shortUrl' => env("APP_URL") . "/" . ($this->collection_id ? $this->collection()->get()[0]->name . "/" : "") . $this->short_url,
            'collectionId' => $this->collection_id,
        ];
    }
    public function transformList(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'originalUrl' => $this->original_url,
            'shortUrl' => env("APP_URL") . "/" . ($this->collection_id ? $this->collection()->get()[0]->name . "/" : "") . $this->short_url,
            'collection' => $this->collection_id ? $this->collection()->get()[0]->transformSimple() : null,
        ];
    }
}

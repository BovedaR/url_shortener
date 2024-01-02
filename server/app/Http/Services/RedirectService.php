<?php
namespace App\Http\Services;

use App\Models\Url;

class RedirectService
{
    public function findUrlByCollectionAndShortUrl($collection, $shortUrl)
    {
        return Url::whereHas('collection', function ($query) use ($collection) {
            $query->where('name', $collection);
        })->where('short_url', $shortUrl)->first();
    }

    public function findUrlByShortUrl($shortUrl)
    {
        return Url::where('short_url', $shortUrl)->where('collection_id', null)->first();
    }
}
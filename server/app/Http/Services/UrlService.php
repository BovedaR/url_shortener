<?php

namespace App\Http\Services;

use App\Models\Url;
use App\Models\UrlCollection;
use Illuminate\Http\Request;

class UrlService
{
    protected $googleSafeBrowsingService;
    protected $user;

    public function __construct($user)
    {
        $this->googleSafeBrowsingService = new GoogleSafeBrowsingService();
        $this->user = $user;
    }

    public function getAllUrls()
    {
        // only return urls that belong to the current user
        $urls = Url::where('user_id', $this->user->id)->get()->transform(function ($url) {
            return $url->transformList();
        });

        return $urls;
    }

    public function createUrl(Request $request)
    {
        $url = new Url;
        $url->name = $request->name;
        $url->original_url = $request->originalUrl;
        $url->collection_id = $request->collectionId ? $request->collectionId : null;

        // Validations
        $request->validate([
            'name' => 'required|string',
            'originalUrl' => 'required|string',
            'collectionId' => 'integer|nullable',
        ]);

        // Check if url is safe
        if (!$this->googleSafeBrowsingService->isUrlSafe($url->original_url)) {
            return ['message' => 'Url is not safe', 'status' => 422];
        }

        // Check if exists another url with the same original url and user id
        $urlExists = Url::where('original_url', $url->original_url)->where('user_id', '==', $this->user->id)->first();
        if ($urlExists) {
            return ['message' => 'Url already exists', 'id' => $urlExists->id, 'status' => 409];
        }

        // Check if collection exists
        if ($url->collection_id != null){
            $collection = UrlCollection::find($url->collection_id);
            if (!$collection) {
                return ['message' => 'Collection not found', 'status' => 404];
            } else {
                $url->collection()->associate($collection);
            }
        }
        
        // Generate short url 6 characters long hash using sha 256 from original url
        $url->short_url = substr(hash('sha256', $url->original_url), 0, 6);

        //get the current user and associate it with the url
        $url->user()->associate($this->user);

        $url->save();

        return ['message' => 'Url created successfully', 'url' => $url];
    }

    public function getUrlById(Url $url)
    {
        // Check if url exists
        if (!$url) {
            return ['message' => 'Url not found', 'status' => 404];
        }

        // Check if user is the owner of the url
        if ($url->user_id != $this->user->id) {
            return ['message' => 'Url does not belong to the current user', 'status' => 403];
        }

        return $url->transformFull();
    }

    public function updateUrl(Request $request, Url $url)
    {
        // Check if url exists
        if (!$url) {
            return ['message' => 'Url not found', 'status' => 404];
        }

        // Check if user is the owner of the url
        if ($url->user_id != $this->user->id) {
            return ['message' => 'Url does not belong to the current user', 'status' => 403];
        }

        // Validations
        $request->validate([
            'name' => 'string',
            'originalUrl' => 'string',
            'collectionId' => 'integer|nullable',
        ]);

        if ($request->name) {
            $url->name = $request->name;
        }
        if ($request->originalUrl) {
            $url->original_url = $request->originalUrl;

            // Check if url is safe
            if (!$this->googleSafeBrowsingService->isUrlSafe($url->original_url)) {
                return ['message' => 'Url is not safe', 'status' => 422];
            }

            // Check if exists another url with the same original url and user id
            $urlExists = Url::where('original_url', $url->original_url)->where('user_id', '==', $this->user->id)->first();
            if ($urlExists) {
                return ['message' => 'Url already exists', 'id' => $urlExists->id, 'status' => 409];
            }
        }
        if ($request->collectionId) {
            $url->collection_id = $request->collectionId;

            // Check if collection exists
            $collection = UrlCollection::find($url->collection_id);
            if (!$collection) {
                return ['message' => 'Collection not found', 'status' => 404];
            } else {
                $url->collection()->associate($collection);
            }
        }
        else {
            $url->collection()->dissociate();
        }

        $url->save();

        return ['message' => 'Url updated successfully', 'url' => $url];
    }

    public function deleteUrl(Url $url)
    {
        // Check if url exists
        if (!$url) {
            return ['message' => 'Url not found', 'status' => 404];
        }

        // Check if user is the owner of the url
        if ($url->user_id != $this->user->id) {
            return ['message' => 'Url does not belong to the current user', 'status' => 403];
        }

        $url->delete();

        return ['message' => 'Url deleted successfully', 'url' => $url];
    }
}
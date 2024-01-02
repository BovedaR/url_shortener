<?php

namespace App\Http\Services;

use App\Models\UrlCollection;
use Illuminate\Http\Request;

class UrlCollectionService
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getAllUrlCollections()
    {
        // only return urlCollections that belong to the current user
        $urlCollections = UrlCollection::where('user_id', $this->user->id)->get();
        return $urlCollections->transform(function ($urlCollection) {
            return $urlCollection->transformSimple();
        });
    }

    public function createUrlCollection(Request $request)
    {
        $urlCollection = new UrlCollection;
        $urlCollection->name = $request->name;

        // Validations
        $request->validate([
            'name' => 'required|string',
        ]);

        // Check if exists another urlCollection with the same name and same user id
        $urlCollectionExists = UrlCollection::where('name', $urlCollection->name)->where('user_id', $this->user->id)->first();
        if ($urlCollectionExists) {
            return ['message' => 'UrlCollection already exists', 'id' => $urlCollectionExists->id, 'status' => 409];
        }

        $urlCollection->user()->associate($this->user);

        $urlCollection->save();

        return ['message' => 'UrlCollection created successfully', 'urlCollection' => $urlCollection];
    }

    public function getUrlCollectionById(UrlCollection $urlCollection)
    {
        if (!$urlCollection) {
            return ['message' => 'UrlCollection not found', 'status' => 404];
        }

        // Check if urlCollection belongs to the current user
        if ($urlCollection->user_id != $this->user->id) {
            return ['message' => 'UrlCollection does not belong to the current user', 'status' => 403];
        }

        return $urlCollection->transformFull();
    }

    public function updateUrlCollection(Request $request, UrlCollection $urlCollection)
    {
        // Check if urlCollection exists
        if (!$urlCollection) {
            return ['message' => 'UrlCollection not found', 404];
        }

        // Check if urlCollection belongs to the current user
        if ($urlCollection->user_id != $this->user->id) {
            return ['message' => 'UrlCollection does not belong to the current user', 'status' => 403];
        }

        $urlCollection->name = $request->name;

        // Validations
        $request->validate([
            'name' => 'string',
        ]);

        // Check if exists another urlCollection with the same name and same user id
        $urlCollectionExists = UrlCollection::where('name', $urlCollection->name)->where('user_id', $this->user->id)->first();
        if ($urlCollectionExists) {
            return ['message' => 'UrlCollection already exists', 'id' => $urlCollectionExists->id, 'status' => 409];
        }

        $urlCollection->save();

        return ['message' => 'UrlCollection updated successfully', 'urlCollection' => $urlCollection];
    }

    public function deleteUrlCollection(UrlCollection $urlCollection)
    {
        // Check if urlCollection exists
        if (!$urlCollection) {
            return ['message' => 'UrlCollection not found', 'status' => 404];
        }

        // Check if urlCollection has associated urls
        if ($urlCollection->urls()->count() > 0) {
            return ['message' => 'UrlCollection has associated urls', 'status' => 422];
        }

        // Check if urlCollection belongs to the current user
        if ($urlCollection->user_id != $this->user->id) {
            return ['message' => 'UrlCollection does not belong to the current user', 'status' => 403];
        }

        $urlCollection->delete();

        return ['message' => 'UrlCollection deleted successfully', 'urlCollection' => $urlCollection];
    }
}
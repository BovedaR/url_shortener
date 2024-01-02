<?php

namespace App\Http\Services;

class GoogleSafeBrowsingService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_SAFE_BROWSING_API_KEY');
    }

    public function isUrlSafe($url)
    {
        $requestData = [
            'client' => [
                'clientId' => env('GOOGLE_APPLICATION_CLIENT_ID'),
                'clientVersion' => '1.0.0',
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION', 'THREAT_TYPE_UNSPECIFIED'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url],
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $jsonResponse = json_decode($response);

        $threatMatches = $jsonResponse->matches ?? [];

        return empty($threatMatches);
    }
}

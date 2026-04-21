<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;

class FcmService
{
    protected $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase/service-account.json');
    }

//    public function sendNotification($deviceToken, $title, $body, $data = [])
//     {
//         $client = new \Google\Client();
//         $client->setAuthConfig($this->credentialsPath);
//         $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//         $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

//         $projectId = json_decode(file_get_contents($this->credentialsPath), true)['project_id'];

//         $message = [
//             'message' => [
//                 'token' => $deviceToken,
//                 'notification' => [
//                     'title' => $title,
//                     'body' => $body,
//                 ],
//                 'data' => $data,
//                 'android' => [
//                     'priority' => 'high',
//                     'notification' => [
//                         'sound' => 'default',
//                         'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                     ],
//                 ],
//                 'apns' => [
//                     'payload' => [
//                         'aps' => [
//                             'sound' => 'default',
//                             'content-available' => 1,
//                         ],
//                     ],
//                 ],
//             ],
//         ];

//         $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
//             ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

//         return $response->json();
//     }
public function sendNotification($deviceToken, $title, $body, $data = [])
{
    $client = new \Google\Client();
    $client->setAuthConfig($this->credentialsPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

    $projectId = json_decode(file_get_contents($this->credentialsPath), true)['project_id'];

    // Convert all data values to strings
    $stringData = [];
    foreach ($data as $key => $value) {
        $stringData[$key] = (string) $value;
    }

    $message = [
        'message' => [
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $stringData,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'sound' => 'default',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'channel_id' => 'high_importance_channel', 
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                        'content-available' => 1,
                    ],
                ],
            ],
        ],
    ];

    $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
        ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

    return $response->json();
}

}

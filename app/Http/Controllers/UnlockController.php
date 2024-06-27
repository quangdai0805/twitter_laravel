<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
class UnlockController extends Controller
{
    //
    private $apiKey;
    private $proxy;

    public function __construct()
    {
        $this->apiKey = 'CAP-B297817280A9A6B7DA09AE5EF91A8A43'; // Thay bằng API key của bạn
        $this->proxy = 'beoxiycq:ch6mxkmwlpbg@154.9.177.180:5460'; // Thay bằng proxy của bạn
    }

    public function getCaptchaKey()
    {
        Log::info("SOLVING CAPTCHA...");
        $urlCreateTask = "https://api.capsolver.com/createTask";
        $urlGetTaskResult = "https://api.capsolver.com/getTaskResult";

        $payloadCreateTask = [
            "clientKey" => $this->apiKey,
            "task" => [
                "type" => "FunCaptchaTask",
                "websitePublicKey" => "0152B4EB-D2DC-460A-89A1-629838B529C9",
                "websiteURL" => "https://twitter.com/account/access",
                "proxy" => $this->proxy
            ]
        ];

        $client = new Client();
        $headers = ['Content-Type' => 'application/json'];

        // Create task
        $responseCreateTask = $client->post($urlCreateTask, [
            'headers' => $headers,
            'json' => $payloadCreateTask
        ]);

        $resultCreateTask = json_decode($responseCreateTask->getBody(), true);
        $taskId = $resultCreateTask['taskId'];

        $payloadGetTaskResult = [
            "taskId" => $taskId,
            "clientKey" => $this->apiKey
        ];

        // Poll for result
        while (true) {
            $responseGetTaskResult = $client->post($urlGetTaskResult, [
                'headers' => $headers,
                'json' => $payloadGetTaskResult
            ]);

            $resultGetTaskResult = json_decode($responseGetTaskResult->getBody(), true);

            if ($resultGetTaskResult['status'] == 'ready') {
                return $resultGetTaskResult['solution']['token'];
            }

            sleep(1); // Chờ 1 giây trước khi kiểm tra lại
        }
    }

    private function extractTokensFromAccessHtmlPage($html)
    {
        preg_match_all('/name="authenticity_token" value="([^"]+)"|name="assignment_token" value="([^"]+)"/', $html, $matches);
        
        if (!empty($matches[1][0]) && !empty($matches[2][0])) {
            $authenticityToken = $matches[1][0];
            $assignmentToken = $matches[2][0];
            return [
                "authenticity_token" => $authenticityToken,
                "assignment_token" => $assignmentToken
            ];
        }

        return null;
    }

    private function getAccessPage($client, $cookies)
    {
        $response = $client->get('https://twitter.com/account/access', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
                'Accept' => '*/*',
                'Accept-Language' => 'en-GB,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'DNT' => '1',
                'Sec-GPC' => '1',
                'Connection' => 'keep-alive',
                'Sec-Fetch-Dest' => 'script',
                'Sec-Fetch-Mode' => 'no-cors',
                'Sec-Fetch-Site' => 'same-origin',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
                'TE' => 'trailers'
            ],
            'cookies' => $cookies,
            'proxy' => $this->proxy
        ]);

        $html = (string) $response->getBody();
        return $this->extractTokensFromAccessHtmlPage($html);
    }

    private function postToAccessPage($client, $cookies, $data)
    {
        $response = $client->post('https://twitter.com/account/access?lang=en', [
            'headers' => [
                'Host' => 'twitter.com',
                'Origin' => 'https://twitter.com',
                'Referer' => 'https://twitter.com/account/access',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
                'Accept' => '*/*',
                'Accept-Language' => 'en-GB,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'DNT' => '1',
                'Sec-GPC' => '1',
                'Connection' => 'keep-alive',
                'Sec-Fetch-Dest' => 'script',
                'Sec-Fetch-Mode' => 'no-cors',
                'Sec-Fetch-Site' => 'same-origin',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
                'TE' => 'trailers'
            ],
            'form_params' => $data,
            'cookies' => $cookies,
            'proxy' => $this->proxy
        ]);

        $html = (string) $response->getBody();
        return $this->extractTokensFromAccessHtmlPage($html);
    }

    public function unlockAccount()
    {
        // $authToken = $request->input('auth_token');
        // $ct0 = $request->input('ct0');
        // $proxy = $request->input('proxy', $this->proxy);

        $authToken = '1089b92a1cf4af341a9ea4310dfb819c0db3991f';
        $ct0 = '9a43545c35c48444dd0d8a4e623aec9b';
        $proxy = '';
        // Giả lập cookies
        $cookies = [
            "auth_token" => $authToken,
            "ct0" => $ct0
        ];

        $client = new Client(['cookies' => true]);

        Log::info("UNLOCKING ACCOUNT...");

        $tokens = $this->getAccessPage($client, $cookies);

        $dataJsInst = [
            "authenticity_token" => $tokens["authenticity_token"],
            "assignment_token" => $tokens["assignment_token"],
            "lang" => "en",
            "flow" => ""
        ];
        $this->postToAccessPage($client, $cookies, $dataJsInst);

        $captchaToken = $this->getCaptchaKey();

        $dataWithToken = [
            "authenticity_token" => $tokens["authenticity_token"],
            "assignment_token" => $tokens["assignment_token"],
            'lang' => 'en',
            'flow' => '',
            'verification_string' => $captchaToken,
            'language_code' => 'en'
        ];
        $this->postToAccessPage($client, $cookies, $dataWithToken);

        $captchaToken = $this->getCaptchaKey();
        $dataWithToken['verification_string'] = $captchaToken;
        $this->postToAccessPage($client, $cookies, $dataWithToken);

        $this->postToAccessPage($client, $cookies, $dataJsInst);

        Log::info("ACCOUNT UNLOCKED...");

        return response()->json(['message' => 'Account unlocked']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;

class UnlockController extends Controller
{
    private $apiKey;
    private $proxy;
    protected $jar;
    private $tokens = [];
    public function __construct()
    {
        $this->apiKey = 'CAP-B297817280A9A6B7DA09AE5EF91A8A43'; // Thay bằng API key của bạn
        $this->proxy = 'beoxiycq:ch6mxkmwlpbg@154.9.177.180:5460'; // Thay bằng proxy của bạn
        $this->jar = new CookieJar();
    }

    public function getCaptchaKey($apiKey, $proxy)
    {
        Log::info("SOLVING CAPTCHA...");
        $urlCreateTask = "https://api.capsolver.com/createTask";
        $urlGetTaskResult = "https://api.capsolver.com/getTaskResult";

        $payloadCreateTask = [
            "clientKey" => $apiKey,
            "task" => [
                "type" => "FunCaptchaTask",
                "websitePublicKey" => "0152B4EB-D2DC-460A-89A1-629838B529C9",
                "websiteURL" => "https://twitter.com/account/access",
                "proxy" => $proxy
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
            "clientKey" => $apiKey
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

            sleep(1);
        }
    }

    private function extractTokensFromAccessHtmlPage(string $html)
    {
        if (preg_match('/<input type="hidden" name="authenticity_token" value="([^"]+)"/', $html, $matches)) {
            $authenticity_token = $matches[1];
        }
        if (preg_match('/<input type="hidden" name="assignment_token" value="([^"]+)"/', $html, $matches)) {
            $assignment_token = $matches[1];
        }

        $this->tokens = [
            "authenticity_token" => $authenticity_token,
            "assignment_token" => $assignment_token
        ];
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
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
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
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
    }

    private function loadCookiesFromDatabase()
    {
        $cookiesString = 'kdt=YRV2iD4IiRb1x5gzeNCesP7DluuUpk6uW2I48qlk; att=; twid="u=1709000880245399552"; ct0=551361b1381b90ba56b030697d7fb132; auth_token=74ef727a9338e6a849975209a7d21b4de86f27a5; ';
        $cookiesString = rtrim($cookiesString, '; ');

        if ($cookiesString) {
            $cookiesArray = explode('; ', $cookiesString);
            // dd($cookiesArray);
            foreach ($cookiesArray as $cookieString) {
                list($name, $value) = explode('=', $cookieString, 2);
                $this->jar->setCookie(new \GuzzleHttp\Cookie\SetCookie(['Name' => $name, 'Value' => $value, 'Domain' => 'twitter.com']));
            }
        }
    }
    public function unlockAccount()
    {

        $this->loadCookiesFromDatabase();
        $client = new Client(['cookies' => $this->jar]);

        Log::info("UNLOCKING ACCOUNT...");


        $this->getAccessPage($client, $this->jar);

        $dataJsInst = [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            "lang" => "en",
            "flow" => ""
        ];

        $this->postToAccessPage($client, $this->jar, $dataJsInst);

        $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);

        $dataWithToken = [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            'lang' => 'en',
            'flow' => '',
            'verification_string' => $captchaToken,
            'language_code' => 'en'
        ];
        $this->postToAccessPage($client, $this->jar, $dataWithToken);

        $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);
        $dataWithToken['verification_string'] = $captchaToken;
        $reslut = $this->postToAccessPage($client, $this->jar, $dataWithToken);
        $result2 =  $this->postToAccessPage($client, $this->jar, $dataJsInst);

        dd($reslut,$result2);

        Log::info("ACCOUNT UNLOCKED...");

        return response()->json(['message' => 'Account unlocked']);
    }
}

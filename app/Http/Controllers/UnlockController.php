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
    protected $client;
    private $tokens = [];

    public function __construct()
    {
        $this->jar = new CookieJar();
        $this->loadCookiesFromDatabase();
        $this->client = new Client(['cookies' => $this->jar]);
        

        $this->apiKey = 'CAP-B297817280A9A6B7DA09AE5EF91A8A43'; // Thay bằng API key của bạn
        $this->proxy = 'beoxiycq:ch6mxkmwlpbg@154.9.177.238:5518'; // Thay bằng proxy của bạn
    }

    public function getCaptchaKey($apiKey, $proxy)
    {
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

        $headers = ['Content-Type' => 'application/json'];

        // Create task
        $responseCreateTask = $this->client->post($urlCreateTask, [
            'headers' => $headers,
            'json' => $payloadCreateTask
        ]);

        $resultCreateTask = json_decode($responseCreateTask->getBody(), true);
        $taskId = $resultCreateTask['taskId'];

        $payloadGetTaskResult = [
            "taskId" => $taskId,
            "clientKey" => $apiKey
        ];

        while (true) {
            $responseGetTaskResult = $this->client->post($urlGetTaskResult, [
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

    private function getAccessPage()
    {
        $response = $this->client->get('https://twitter.com/account/access', [
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
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]);
        $html = (string) $response->getBody();
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
    }

    private function postTo_ContinuePage($data)
    {
        $response = $this->client->post('https://twitter.com/account/access', [
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
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]);

        $html = (string) $response->getBody();
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
    }

    private function postToAccessPage($data)
    {


        \Log::info(json_encode(['https://twitter.com/account/access?lang=en', [
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
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]], JSON_PRETTY_PRINT));


        $response = $this->client->post('https://twitter.com/account/access?lang=en', [
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
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]);


        // dd($response);
        $html = (string) $response->getBody();
        $this->extractTokensFromAccessHtmlPage($html);
        return ;
    }

    private function loadCookiesFromDatabase()
    {
        $cookiesString = 'gt=1808804275298127922; kdt=f4xKrACVl2Tu8r6Ep5Zdkc4432lryib7M7C5E4Bd; att=; twid="u=1649761859699093504"; ct0=db8d5c6e9638b0e292ed2bef5ab9697b; auth_token=e21dda8aeec6aceabe711f89a78d3eb9fa47a500; ';
        $cookiesString = rtrim($cookiesString, '; ');

        if ($cookiesString) {
            $cookiesArray = explode('; ', $cookiesString);
            foreach ($cookiesArray as $cookieString) {
                list($name, $value) = explode('=', $cookieString, 2);
                $this->jar->setCookie(new \GuzzleHttp\Cookie\SetCookie(['Name' => $name, 'Value' => $value, 'Domain' => 'twitter.com']));
            }
        }
    }

    private function postDataWithJsInst()
    {
        $dataJsInst = [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            "lang" => "en",
            "flow" => ""
        ];

        return $this->postToAccessPage($dataJsInst);
    }

    private function postDataWithToken(string $funCaptchaToken)
    {
        // $data = $this->dataWithFuncaptcha($this->tokens, $funCaptchaToken);

        $dataWithToken = [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            'lang' => 'en',
            'flow' => '',
            'verification_string' => $funCaptchaToken,
            'language_code' => 'en'
        ];

        $this->postToAccessPage($dataWithToken);
    }

    public function unlockAccount()
    {

        $str = $this->client->post('https://twitter.com/account/access', json_decode('{
            "headers": {
                "Host": "twitter.com",
                "Origin": "https:\/\/twitter.com",
                "Referer": "https:\/\/twitter.com\/account\/access",
                "User-Agent": "Mozilla\/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko\/20100101 Firefox\/123.0",
                "Accept": "*\/*",
                "Accept-Language": "en-GB,en;q=0.5",
                "Accept-Encoding": "gzip, deflate, br",
                "DNT": "1",
                "Sec-GPC": "1",
                "Connection": "keep-alive",
                "Sec-Fetch-Dest": "script",
                "Sec-Fetch-Mode": "no-cors",
                "Sec-Fetch-Site": "same-origin",
                "Pragma": "no-cache",
                "Cache-Control": "no-cache",
                "TE": "trailers"
            },
            "form_params": {
                "authenticity_token": "661d41d1d1e76efb198a5d57af65e859a6abde5b",
                "assignment_token": "-243758725",
                "lang": "en",
                "flow": ""
            },
            "cookies": {},
            "proxy": "beoxiycq:ch6mxkmwlpbg@154.9.177.238:5518"
        }', true));

        // $this->getAccessPage();
        // $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);
        // $this->postDataWithToken($captchaToken);
        // $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);
        // $this->postDataWithToken($captchaToken);

        // $result = $this->postDataWithJsInst();
        // dd($reslut);
        return response()->json(['message' => 'Account unlocked']);
    }
}

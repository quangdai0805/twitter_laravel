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
        $this->client = new Client(['cookies' => $this->jar,
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
        ]);
        

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

    private function __js_inst()
    {
        $response = $this->client->get('https://twitter.com/i/js_inst?c_name=ui_metrics', [
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]);
        $js_script = $response->getBody();
        $pattern = '/return\s*({.*?});/s'; 
        preg_match($pattern, $js_script, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }
        return null; 
    }

    private function __post_data_with_js_inst(){
        $data = $this->__data_with_js_inst();
        return $this->postToAccessPage($data);
    }


    private function  __data_with_js_inst(){

        return [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            "lang" => "en",
            "flow" => "",
            "ui_metrics" =>  $this->__js_inst(),
        ];
    }
    
    private function getAccessPage()
    {
        $response = $this->client->get('https://twitter.com/account/access', [
            'cookies' => $this->jar,
            'proxy' => $this->proxy
        ]);
        $html = (string) $response->getBody();
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
    }

    private function postTo_ContinuePage()
    {
        // $headers = [
        //     "authority" => "twitter.com",
        //     "method" => "POST",
        //     "path" => "/account/access",
        //     "scheme" => "https",
        //     "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
        //     "Accept-Language" => "en-US,en;q=0.9",
        //     "Cache-Control" => "max-age=0",
        //     "Content-Length" => "818",
        //     "Content-Type" => "application/x-www-form-urlencoded",
        //     "Origin" => "https://twitter.com",
        //     "Referer" => "https://twitter.com/account/access",
        //     "Sec-Ch-Ua" => "\"Microsoft Edge\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"",
        //     "Sec-Ch-Ua-Mobile" => "?0",
        //     "Sec-Ch-Ua-Platform" => "\"Windows\"",
        //     "Sec-Fetch-Dest" => "document",
        //     "Sec-Fetch-Mode" => "navigate",
        //     "Sec-Fetch-Site" => "same-origin",
        //     "Sec-Fetch-User" => "?1",
        //     "Upgrade-Insecure-Requests" => "1",
        // ];

        // try {
        //     // Send asynchronous POST request
        //     $response = $this->client->post("https://twitter.com/account/access", [
        //         'headers' => $headers,
        //         'form_params' => $formData,
        //         'cookies' => $this->jar,
        //         'proxy' => $this->proxy
        //     ]);
        //     dd($response);
        //     // Get response body
        //     // $body = await $response->getBody()->getContents();
    
        //     // Return response and body
        //     return ;
    
        // } catch (RequestException $e) {
        //     // Handle request exceptions if needed
        //     echo "Request failed: " . $e->getMessage();
        //     dd($e->getMessage());
        //     // return [null, null];
        // }
        $formData = [
            "authenticity_token" => $this->tokens["authenticity_token"],
            "assignment_token" => $this->tokens["assignment_token"],
            "lang" => "en",
            "flow" => "",
        ];
        try {
            $response = $this->client->post('https://twitter.com/account/access', [
                'form_params' => $formData,
                'cookies' => $this->jar,
                'proxy' => $this->proxy
            ]);
            dd($response);
        } catch (RequestException $e) {
                    echo "Request failed: " . $e->getMessage();
                    dd($e->getMessage());
                }

        $html = (string) $response->getBody();
        $this->extractTokensFromAccessHtmlPage($html);
        return $html;
    }

    private function postToAccessPage($data)
    {
        try {
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
            $html = (string) $response->getBody();
            $this->extractTokensFromAccessHtmlPage($html);
            return $html;
        } catch (RequestException $e) {
            echo "Request failed: " . $e->getMessage();
            dd($e->getMessage());
        }
       
    }

    private function loadCookiesFromDatabase()
    {
        $cookiesString = 'gt=1809048646526382371; kdt=139lkJ1ZLZtjLKRrg3ybwh3LhTILNjS6ridBgbZJ; att=; twid="u=1649727161178415106"; ct0=ba70662080f33161616bf754befc2173; auth_token=58dbf215663ef5670e9121f9785bd58f98258f13;	';
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

        return $this->postToAccessPage($dataWithToken);
    }

    public function unlockAccount()
    {
        $this->getAccessPage();
        $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);
        $this->postDataWithToken($captchaToken);

        $captchaToken = $this->getCaptchaKey($this->apiKey, $this->proxy);
        $html =  $this->postDataWithToken($captchaToken);

        $this->postDataWithJsInst();

        dd($reslut);
        return response()->json(['message' => 'Account unlocked']);
    }
}

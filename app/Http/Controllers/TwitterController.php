<?php

namespace App\Http\Controllers;

use App\Models\Account;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use OTPHP\TOTP;
use Illuminate\Http\Request;

class TwitterController extends Controller
{
    private Account $account;
    private $headerCookies = '';
    private $guestToken = '';
    private $flowToken = '';
    protected $client;
    protected $jar;

    private $url = 'https://twitter.com/account/access';
    private $tokens = [];
    public function __construct()
    {
        $this->jar = new CookieJar();
        $this->client = new Client([
            'base_uri' => 'https://api.x.com',
            'timeout'  => 10.0,
        ]);
    }

    public function CheckProxy()
    {
        return $this->sendRequest('POST', 'http://ip-api.com/json', $this->get_headers());
    }

    public function get_2fa_code($secret)
    {
        try {
            $otp = TOTP::create($secret)->now();
            return $otp;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }


    public function get_headers()
    {
        return [
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'Content-Type' => 'application/json',
            'User-Agent' => 'TwitterAndroid/9.95.0-release.0 (29950000-r-0) ONEPLUS+A3010/9 (OnePlus;ONEPLUS+A3010;OnePlus;OnePlus3;0;;1;2016)',
            'X-Twitter-API-Version' => '5',
            'X-Twitter-Client' => 'TwitterAndroid',
            'X-Twitter-Client-Version' => '9.95.0-release.0',
            'OS-Version' => '28',
            'System-User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 9; ONEPLUS A3010 Build/PKQ1.181203.001)',
            'X-Twitter-Active-User' => 'yes',
            'X-Guest-Token' => $this->guestToken,
        ];
    }
    public function postTweet($message)
    {
        return 'Tweet posted: ' . $message;
    }

    function getCookieValue($cookies, $cookieName)
    {
        preg_match("/{$cookieName}=([^;]*)/", $cookies, $matches);
        return $matches[1] ?? null;
    }

    function setCookieValue($cookies, $cookieName, $cookieValue)
    {
        // Sử dụng regex để thay thế giá trị của cookie
        return preg_replace(
            "/{$cookieName}=([^;]*)/",
            "{$cookieName}={$cookieValue}",
            $cookies
        );
    }



    public function UpdateProfile()
    {
        $headers = [
            'authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'X-Csrf-Token' => $this->getCookieValue($this->headerCookies, 'ct0'),
            'Cookie' => $this->headerCookies,
            'authority' => 'twitter.com',
            'origin' => 'https://twitter.com',
            'x-twitter-active-user' => 'yes',
            'x-twitter-client-language' => 'en'
        ];
        return $this->sendRequest('POST', 'https://twitter.com/i/api/1.1/account/update_profile.json', $headers);
    }

    public function CreateRetweet(Request $request)
    {

        $postID = "1806502616354238833";
        $this->account = Account::find(28);
        $cookies = $this->account->cookies;
        $this->loadCookiesFromDatabase();
        dd($this->jar);
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/i/api/graphql/ojPdsZsimiJrUGLR1sjUtA/CreateRetweet',
            'scheme' => 'https',
            'accept' => '*/*',
            'accept-encoding' => 'gzip, deflate, br, zstd',
            'accept-language' => 'en-US,en;q=0.9',
            'authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'content-length' => '1322',
            'content-type' => 'application/json',
            // 'cookie' => $cookies,
            'origin' => 'https://twitter.com',
            'referer' => 'https://twitter.com/home',
            'x-csrf-token' => $this->getCookieValue($cookies, 'ct0'),
            'x-twitter-active-user' => 'yes',
            'x-twitter-auth-type' => 'OAuth2Session',
            'x-twitter-client-language' => 'en',
        ];
        $json = json_encode([
            'variables' => [
                'tweet_id' => $postID
            ],
            'queryId' => 'ojPdsZsimiJrUGLR1sjUtA'
        ]);
        $result =  $this->sendRequest('POST', 'https://twitter.com/i/api/graphql/ojPdsZsimiJrUGLR1sjUtA/CreateRetweet', $headers, $json);

    }


    public function LikePost(Request $request)
    {
        // $listAccount = $request->selected_accounts;
        // dd($listAccount);


        $postID = $request->input('postid');


        $this->account = Account::find(26);
        $rrr = $this->RequestLikePost($this->account,$postID);
        dd($rrr);


        $cookies = $this->account->cookies;
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/i/api/graphql/OLVH4dMqf6VyvX-XX30pRw/CreateTweet',
            'scheme' => 'https',
            'accept' => '*/*',
            'accept-encoding' => 'gzip, deflate, br, zstd',
            'accept-language' => 'en-US,en;q=0.9',
            'authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'content-length' => '1322',
            'content-type' => 'application/json',
            'cookie' => $cookies,
            'origin' => 'https://twitter.com',
            'referer' => 'https://twitter.com/home',
            'x-csrf-token' => $this->getCookieValue($cookies, 'ct0'),
            'x-twitter-active-user' => 'yes',
            'x-twitter-auth-type' => 'OAuth2Session',
            'x-twitter-client-language' => 'en',
        ];
        $json = json_encode([
            'variables' => [
                'tweet_id' => $postID
            ],
            'queryId' => 'lI07N6Otwv1PhnEgXILM7A'
        ]);
        return $this->sendRequest('POST', 'https://x.com/i/api/graphql/lI07N6Otwv1PhnEgXILM7A/FavoriteTweet', $headers, $json);
    }
    
    public function RequestLikePost($account, $postID)
    {


        $cookies = $account->cookies;
        $proxy = $account->proxy;

        $client = new Client([
            'base_uri' => 'https://api.x.com',
            'timeout'  => 10.0,
        ]);
        $jar = $this->loadCookies('gt=1806495768804814934; kdt=Oa3UkeujYSML4uL3ZTteM2ofBzEKxxVM3WM3XO3i; twid="u=1709038236667105280"; att=; ct0=aecaee140d6e8029ecb0b5cc21bc29c4; auth_token=bd995dc4441cd759925731d8b8c2ab9814150d46; ');
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/i/api/graphql/OLVH4dMqf6VyvX-XX30pRw/CreateTweet',
            'scheme' => 'https',
            'accept' => '*/*',
            'accept-encoding' => 'gzip, deflate, br, zstd',
            'accept-language' => 'en-US,en;q=0.9',
            'authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'content-length' => '1322',
            'content-type' => 'application/json',
            'origin' => 'https://twitter.com',
            'referer' => 'https://twitter.com/home',
            'x-csrf-token' => $this->getCookieValue($cookies, 'ct0'),
            'x-twitter-active-user' => 'yes',
            'x-twitter-auth-type' => 'OAuth2Session',
            'x-twitter-client-language' => 'en',
        ];
        $body = json_encode([
            'variables' => [
                'tweet_id' => $postID
            ],
            'queryId' => 'lI07N6Otwv1PhnEgXILM7A'
        ]);

        return $this->sendRequestBase($client,'POST', 'https://x.com/i/api/graphql/lI07N6Otwv1PhnEgXILM7A/FavoriteTweet', $headers, $body,null,$jar,$proxy);

    }

    public function LoginAccount(Request $request)
    {

        $output = '';
        $this->account = Account::find(27);
        $result = $this->initGuestToken();
        $response = json_decode($result['response'], true);
        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }


        $this->guestToken = $response['guest_token'];
        $this->headerCookies .= "gt={$this->guestToken};";

        $result = $this->flowStart();

        $response = json_decode($result['response'], true);


        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];

        $result = $this->flowInstrumentation();
        $response = json_decode($result['response'], true);


        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];
        $result = $this->flowUsername();


        $response = json_decode($result['response'], true);

        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];

        $result = $this->flowPassword($this->account->password);

        $response = json_decode($result['response'], true);


        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];



        $result = $this->flowDuplicationCheck();
        $response = json_decode($result['response'], true);



        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];

        $result = $this->confirmEmail($this->account->twofa);
        $response = json_decode($result['response'], true);


        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }
        $output = $response['subtasks'][0]['subtask_id'] ?? '';
        if ($output == 'LoginSuccessSubtask') {
            if (isset($result['headers']['set-cookie'])) {
                foreach ($result['headers']['set-cookie'] as $cookie) {
                    $parts = explode(';', $cookie);
                    $this->headerCookies .= "{$parts[0]}; ";
                }
            }
        }

        $this->account->cookies = $this->headerCookies;
        $this->account->save();
        $cookiesArray = $this->jar->toArray();
        $cookiesJson = json_encode($cookiesArray);
        dd($cookiesJson);



        // $result = $this->UpdateProfile();
        // if ($result['status'] === 200) {
        //     if (isset($result['headers']['set-cookie'])) {
        //         foreach ($result['headers']['set-cookie'] as $cookie) {
        //             $parts = explode(';', $cookie);
        //             if (strpos($parts[0], 'ct0') !== false) {
        //                 $ct0 = str_replace('ct0=', '', $parts[0]);
        //                 $this->headerCookies = $this->setCookieValue($this->headerCookies, "ct0", $ct0);
        //             }
        //         }
        //     }
        // }



        $this->unlock();



        return $output;
    }



    //Func Unlock

    private function saveCookiesToDatabase(CookieJar $cookieJar)
    {
        $cookiesArray = $cookieJar->toArray();
        $cookiesString = '';

        foreach ($cookiesArray as $cookie) {
            $cookiesString .= $cookie['Name'] . '=' . $cookie['Value'] . '; ';
        }

        // Remove the last semicolon and space
        $cookiesString = rtrim($cookiesString, '; ');

        $this->account->cookies = $cookiesString;
        $this->account->save();
    }

    private function loadCookiesFromDatabase()
    {
        $cookiesString = 'kdt=Oa3UkeujYSML4uL3ZTteM2ofBzEKxxVM3WM3XO3i; twid="u=1709038236667105280"; att=; ct0=aecaee140d6e8029ecb0b5cc21bc29c4; auth_token=bd995dc4441cd759925731d8b8c2ab9814150d46; ';
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

    private function loadCookies($cookiesString)
    {
        $cookieJar = new CookieJar();
        $cookiesString = rtrim($cookiesString, '; ');
        if ($cookiesString) {
            $cookiesArray = explode('; ', $cookiesString);
            foreach ($cookiesArray as $cookieString) {
                list($name, $value) = explode('=', $cookieString, 2);
                $cookieJar->setCookie(new \GuzzleHttp\Cookie\SetCookie(['Name' => $name, 'Value' => $value, 'Domain' => 'twitter.com']));
            }
        }
        return $cookieJar;
    }

    public function Header_Unlock()
    {
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/account/access?lang=en',
            'scheme' => 'https',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'max-age=0',
            'Content-Length' => '818',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Origin' => 'https://twitter.com',
            'Referer' => 'https://twitter.com/account/access',
            'Sec-Ch-Ua' => '"Microsoft Edge";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua-Platform' => '"Windows"',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-User' => '?1',
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0'
        ];
        return $headers;
    }


    public function accessUnlockCaptcha()
    {
        $client = new Client();

        // Headers
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/account/access?lang=en',
            'scheme' => 'https',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'max-age=0',
            'Content-Length' => '818',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cookie' => $this->headerCookies,
            'Origin' => 'https://twitter.com',
            'Referer' => 'https://twitter.com/account/access',
            'Sec-Ch-Ua' => '"Microsoft Edge";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua-Platform' => '"Windows"',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-User' => '?1',
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'your-user-agent-here'
        ];

        // Lấy captcha token từ QD_Helper
        $captchaToken = $this->getCaptchaKey('CAP-B297817280A9A6B7DA09AE5EF91A8A43', $this->account->proxy);


        // Payload
        $payload = [
            'authenticity_token' => $this->tokens["authenticity_token"],
            'assignment_token' => $this->tokens["assignment_token"],
            'lang' => 'en',
            'verification_string' => $captchaToken,
            'flow' => '',
            'language_code' => 'en'
        ];


        // Gửi yêu cầu POST
        try {
            $response = $client->post('https://twitter.com/account/access?lang=en', [
                'headers' => $headers,
                'form_params' => $payload
            ]);

            return response()->json([
                'status' => 'success',
                'response' => $response->getBody()->getContents()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
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
        //return $this->sendRequest('GET', $this->url, $this->Header_Unlock());
        $response = $this->client->get($this->url, [
            'headers' => [
                "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0",
            ],
            'cookie' => $this->jar,
        ]);
        $this->extractTokensFromAccessHtmlPage((string) $response->getBody());
    }

    private function postToAccessPage(array $data)
    {

        $response = $this->client->post('https://twitter.com/account/access?lang=en', [
            'form_params' => $data,
            'headers' => $this->Header_Unlock(),
            'cookie' => $this->jar
        ]);
        $this->extractTokensFromAccessHtmlPage((string) $response->getBody());
    }

    private function dataWithJsInst(array $tokens)
    {
        return [
            "authenticity_token" => $tokens["authenticity_token"],
            "assignment_token" => $tokens["assignment_token"],
            "lang" => "en",
            "flow" => "",
        ];
    }

    private function dataWithFuncaptcha(array $tokens, string $funCaptchaToken)
    {
        return [
            "authenticity_token" => $tokens["authenticity_token"],
            "assignment_token" => $tokens["assignment_token"],
            'lang' => 'en',
            'flow' => '',
            'verification_string' => $funCaptchaToken,
            'language_code' => 'en'
        ];
    }

    private function postDataWithToken(string $funCaptchaToken)
    {
        $data = $this->dataWithFuncaptcha($this->tokens, $funCaptchaToken);
        $this->postToAccessPage($data);
    }

    private function postDataWithJsInst()
    {
        $data = $this->dataWithJsInst($this->tokens);
        $this->postToAccessPage($data);
    }

    function getCaptchaKey($apiKey, $proxy)
    {
        $urlCreateTask = "https://api.capsolver.com/createTask";
        $urlGetTaskResult = "https://api.capsolver.com/getTaskResult";

        $payloadCreateTask = [
            'clientKey' => $apiKey,
            'task' => [
                'type' => 'FunCaptchaTask',
                'websitePublicKey' => '0152B4EB-D2DC-460A-89A1-629838B529C9',
                'websiteURL' => 'https://twitter.com/account/access',
                'proxy' => $proxy
            ]
        ];


        try {
            $responseCreateTask = $this->client->post($urlCreateTask, [
                'json' => $payloadCreateTask
            ]);

            $resultCreateTask = json_decode($responseCreateTask->getBody(), true);
            $taskId = $resultCreateTask['taskId'];

            $payloadGetTaskResult = [
                'taskId' => $taskId,
                'clientKey' => $apiKey
            ];

            while (true) {
                $responseGetTaskResult = $this->client->post($urlGetTaskResult, [
                    'json' => $payloadGetTaskResult
                ]);

                $resultGetTaskResult = json_decode($responseGetTaskResult->getBody(), true);

                if ($resultGetTaskResult['status'] === 'ready') {
                    return $resultGetTaskResult['solution']['token'];
                }

                sleep(1); // Chờ 1 giây trước khi kiểm tra lại
            }
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function unlock()
    {
        $this->getAccessPage();
        $this->postDataWithJsInst();
        $funCaptchaToken = $this->getCaptchaKey('CAP-B297817280A9A6B7DA09AE5EF91A8A43', $this->account->proxy);
        $this->postDataWithToken($funCaptchaToken);
        $funCaptchaToken = $this->getCaptchaKey('CAP-B297817280A9A6B7DA09AE5EF91A8A43', $this->account->proxy);
        $this->postDataWithToken($funCaptchaToken);
        $this->postDataWithJsInst();
    }


    //End Unlock





    private function initGuestToken()
    {
        $headerAccess = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'content-type' => 'application/json',
            'X-Twitter-Active-User' => 'yes',
            'X-Twitter-Client-Language' => 'en',
        ];

        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/guest/activate.json', $headerAccess);
    }

    private function flowStart()
    {
        $requestBody = json_encode([
            'flow_token' => null,
            'input_flow_data' => [
                'country_code' => null,
                'flow_context' => [
                    'referrer_context' => [
                        'referral_details' => 'utm_source=google-play&utm_medium=organic',
                        'referrer_url' => ''
                    ],
                    'start_location' => [
                        'location' => 'deeplink'
                    ]
                ],
                'requested_variant' => null,
                'target_user_id' => 0
            ]
        ]);
        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json?flow_name=login', $this->get_headers(), $requestBody);
    }

    private function flowInstrumentation()
    {
        $requestBody = json_encode([
            'flow_token' => $this->flowToken,
            "subtask_inputs" => [
                [
                    "subtask_id" => "LoginJsInstrumentationSubtask",
                    "js_instrumentation" => [
                        "response" => "{}",
                        "link" => "next_link"
                    ]
                ]
            ]

        ]);
        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function flowUsername()
    {
        $requestBody = json_encode([
            "flow_token" => $this->flowToken,
            "subtask_inputs" => [
                [
                    "subtask_id" => "LoginEnterUserIdentifierSSO",
                    "settings_list" => [
                        "setting_responses" => [
                            [
                                "key" => "user_identifier",
                                "response_data" => [
                                    "text_data" => [
                                        "result" => $this->account->username
                                    ]
                                ]
                            ]
                        ],
                        "link" => "next_link"
                    ]
                ]
            ]
        ]);
        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function flowPassword()
    {
        $requestBody = json_encode([
            'flow_token' => $this->flowToken,
            "subtask_inputs" => [
                [
                    "subtask_id" => "LoginEnterPassword",
                    "enter_password" => [
                        "password" => $this->account->password,
                        "link" => "next_link"
                    ]
                ]
            ]
        ]);

        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function flowDuplicationCheck()
    {
        $requestBody = json_encode([
            'flow_token' => $this->flowToken,
            "subtask_inputs" => [
                [
                    "subtask_id" => "AccountDuplicationCheck",
                    "check_logged_in_account" => [
                        "link" => "AccountDuplicationCheck_false"
                    ]
                ]
            ]
        ]);

        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function confirmEmail($twofa)
    {
        $requestBody = json_encode([
            'flow_token' => $this->flowToken,
            "subtask_inputs" => [
                [
                    "subtask_id" => "LoginTwoFactorAuthChallenge",
                    "enter_text" => [
                        "text" => $this->get_2fa_code($twofa),
                        "link" => "next_link"
                    ]
                ]
            ]
        ]);

        return $this->sendRequest('POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function sendRequestBase($client,$method, $url, $headers, $body = null, $param = null,$cookie = null, $proxy)
    {
        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
                'form_params' => $param,
                'cookies' => $cookie,
                'proxy' => [
                    'http'  => "http://$proxy", // Proxy HTTP
                    // 'https' => "https://$proxy", // Proxy HTTPS (nếu cần)
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            return [
                'status' => $response->getStatusCode(),
                'response' => $responseBody,
                'headers' => $response->getHeaders()
            ];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                return [
                    'status' => $e->getResponse()->getStatusCode(),
                    'response' => $responseBody,
                    'headers' => $e->getResponse()->getHeaders()
                ];
            }
            return [
                'status' => 500,
                'response' => 'Internal Server Error',
                'headers' => []
            ];
        }
    }

    private function sendRequest($method, $url, $headers, $body = null, $param = null)
    {
        try {
            $proxy = $this->account->proxy;
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
                'form_params' => $param,
                'cookies' => $this->jar,
                'proxy' => [
                    'http'  => "http://$proxy", // Proxy HTTP
                    // 'https' => "https://$proxy", // Proxy HTTPS (nếu cần)
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            return [
                'status' => $response->getStatusCode(),
                'response' => $responseBody,
                'headers' => $response->getHeaders()
            ];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                return [
                    'status' => $e->getResponse()->getStatusCode(),
                    'response' => $responseBody,
                    'headers' => $e->getResponse()->getHeaders()
                ];
            }
            return [
                'status' => 500,
                'response' => 'Internal Server Error',
                'headers' => []
            ];
        }
    }
}

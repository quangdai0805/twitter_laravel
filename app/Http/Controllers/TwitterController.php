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
        // Your logic here
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
            'X-Csrf-Token' => $this->getCookieValue($this->headerCookies, 'ct0'), // Assuming the CSRF token is stored in a cookie
            'Cookie' => $this->headerCookies, // Assuming the auth token is stored in a cookie
            'authority' => 'twitter.com',
            'origin' => 'https://twitter.com',
            'x-twitter-active-user' => 'yes',
            'x-twitter-client-language' => 'en'
        ];
       return $this->sendRequest('POST', 'https://twitter.com/i/api/1.1/account/update_profile.json', $headers);
    }
    
    public function CreateRetweet()
    {
        $postID = "1804062472048918555";
        $this->account = Account::find(13);
        $cookies = $this->account->cookies;
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
            'cookie' => $cookies,
            'origin' => 'https://twitter.com',
            'referer' => 'https://twitter.com/home',
            'x-csrf-token' => $this->getCookieValue($cookies,'ct0'),
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
        dd($result);
    }

    public function LikePost(Request $request)
    {
        $postID = $request->input('postid');
        $this->account = Account::find(15);
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
            'x-csrf-token' => $this->getCookieValue($cookies,'ct0'),
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

    public function LoginAccount(Request $request)
    {
        $output = '';

        // $accountId = $request->input('accounts');
        $this->account = Account::find(15);
        //dd($this->account);

        $result = $this->initGuestToken();
        // dd($result);
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
        $result = $this->UpdateProfile();
        if($result['status'] === 200){
            if (isset($result['headers']['set-cookie'])) {
                foreach ($result['headers']['set-cookie'] as $cookie) {
                    $parts = explode(';', $cookie);
                if (strpos($parts[0], 'ct0') !== false) {
                    $ct0 = str_replace('ct0=', '', $parts[0]);
                    $this->headerCookies = $this->setCookieValue($this->headerCookies, "ct0", $ct0);

                }

                    
                }
            }
            $this->account->cookies = $this->headerCookies;
            $this->account->save();
        }
      
        dd($result);
        return $output;
    }




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

    private function sendRequest($method, $url, $headers, $body = null)
    {
        try {
            $proxy = $this->account->proxy;
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
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

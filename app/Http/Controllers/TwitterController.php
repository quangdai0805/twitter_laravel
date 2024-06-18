<?php

namespace App\Http\Controllers;

use App\Models\Account;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use OTPHP\TOTP;

class TwitterController extends Controller
{
    //private Account $account;
    private $headerCookies = '';
    private $guestToken = '';
    private $flowToken = '';
    private $url = 'https://api.twitter.com/1.1/onboarding/task.json';
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
    public function LoginAccount(Account $account)
    {
        $output = '';

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

        $result = $this->flowUsername($account->username);
        $response = json_decode($result['response'], true);

        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $this->flowToken = $response['flow_token'];

        $result = $this->flowPassword($account->password);
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

        $result = $this->confirmEmail($account->twofa);
        $response = json_decode($result['response'], true);

        dd($result);

        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }

        $output = $response['subtasks'][0]['subtask_id'] ?? '';
        //dd($result['headers']['set-cookie']);

        if ($output == 'LoginSuccessSubtask') {
            if (isset($result['headers']['set-cookie'])) {
                //dd($result['headers']['set-cookie']);
                foreach ($result['headers']['set-cookie'] as $cookie) {
                    $parts = explode(';', $cookie);
                    $this->headerCookies .= "{$parts[0]}; ";
                }

            }
        }

        dd($this->headerCookies);
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

        return $this->sendRequest($this->client, 'POST', 'https://api.twitter.com/1.1/guest/activate.json', $headerAccess);
    }

    private function flowStart()
    {

        $task1Response = $this->client->post($this->url, [
            'headers' => $this->get_headers(),
            'cookies' => $this->jar,
            'query' => [
                'flow_name' => 'login'
            ],
            'json' => [
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
            ]
        ]);

        return [
            'status' => $task1Response->getStatusCode(),
            'response' => $task1Response->getBody()->getContents(),
            'headers' => $task1Response->getHeaders()
        ];
    }

    private function flowInstrumentation()
    {


        $task1Response = $this->client->post($this->url, [
            'headers' => $this->get_headers(),
            'cookies' => $this->jar,
            'json' => [
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

            ]
        ]);

        return [
            'status' => $task1Response->getStatusCode(),
            'response' => $task1Response->getBody()->getContents(),
            'headers' => $task1Response->getHeaders()
        ];
    }

    private function flowUsername($username)
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
                                        "result" => $username
                                    ]
                                ]
                            ]
                        ],
                        "link" => "next_link"
                    ]
                ]
            ]
        ]);

        $headerAccess = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, như Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'X-Twitter-Active-User' => 'yes',
            'X-Twitter-Client-Language' => 'en',
            'content-type' => 'application/json',
            'x-guest-token' => $this->guestToken,
        ];

        return $this->sendRequest($this->client, 'POST', 'https://api.twitter.com/1.1/onboarding/task.json', $this->get_headers(), $requestBody);
    }

    private function flowPassword($password)
    {

        $response = $this->client->post($this->url, [
            'headers' => $this->get_headers(),
            'cookies' => $this->jar,
            'json' => [
                'flow_token' => $this->flowToken,
                "subtask_inputs" => [
                    [
                        "subtask_id" => "LoginEnterPassword",
                        "enter_password" => [
                            "password" => $password,
                            "link" => "next_link"
                        ]
                    ]
                ]
            ]
        ]);

        $responseBody = $response->getBody()->getContents();
        return [
            'status' => $response->getStatusCode(),
            'response' => $responseBody,
            'headers' => $response->getHeaders()
        ];
    }

    private function flowDuplicationCheck()
    {
        $response = $this->client->post($this->url, [
            'headers' => $this->get_headers(),
            'cookies' => $this->jar,
            'json' => [
                'flow_token' => $this->flowToken,
                "subtask_inputs" => [
                    [
                        "subtask_id" => "AccountDuplicationCheck",
                        "check_logged_in_account" => [
                            "link" => "AccountDuplicationCheck_false"
                        ]
                    ]
                ]
            ]
        ]);

        $responseBody = $response->getBody()->getContents();
        return [
            'status' => $response->getStatusCode(),
            'response' => $responseBody,
            'headers' => $response->getHeaders()
        ];

    }

    private function confirmEmail($twofa)
    {
        $response = $this->client->post($this->url, [
            'headers' => $this->get_headers(),
            'cookies' => $this->jar,
            'json' => [
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
            ]
        ]);
        $responseBody = $response->getBody()->getContents();
        return [
            'status' => $response->getStatusCode(),
            'response' => $responseBody,
            'headers' => $response->getHeaders()
        ];
    }

    private function sendRequest($client, $method, $url, $headers, $body = null)
    {
        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
                'cookies' => $this->jar,
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

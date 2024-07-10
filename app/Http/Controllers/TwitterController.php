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


    public function test(Request $request){
        $accounts = $request->input('accounts');

        $data = [
            'emails' => [
            ]
        ];
        foreach ($accounts as $email) {
            $data['emails'][] = ['email' => $email];
        }

        $client = new Client();

        $url = 'http://103.151.238.225:8000/check_x';
        
        // $data = [
        //     'emails' => [
        //         ['email' => 'ChapelElro33771'],
        //         ['email' => 'MFinamore76769'],
        //         ['email' => 'CassioLaki31169'],
        //     ]
        // ];

        try {
            $response = $client->post($url, [
                'json' => $data
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            return $result;

        } catch (\Exception $e) {
            
            return $e->getMessage();
        }
    }
    

    public function check_x(Request $request){
        $username  = $request->input('username');
        $client = new Client();
        $headerAccess = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, như Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'X-Twitter-Active-User' => 'yes',
            'X-Twitter-Client-Language' => 'en'
        ];
        $options = [
            'headers' => $headerAccess
        ];

        $response = $client->request('POST', 'https://api.twitter.com/1.1/guest/activate.json', $options);
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);


        $response = $client->get("https://api.x.com/graphql/qW5u-DAuXpMEG0zA1F7UGQ/UserByScreenName", [
            'headers'  => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
                'Cookie' => "gt={$data['guest_token']}",
                'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
                'X-Guest-Token' => $data['guest_token'],
            ],
            'query' => [
                'variables' => json_encode([
                    'screen_name' => $username,
                    'withSafetyModeUserFields' => true,
                ]),
                'features' => json_encode([
                    'hidden_profile_likes_enabled' => true,
                    'hidden_profile_subscriptions_enabled' => true,
                    'rweb_tipjar_consumption_enabled' => true,
                    'responsive_web_graphql_exclude_directive_enabled' => true,
                    'verified_phone_label_enabled' => false,
                    'subscriptions_verification_info_is_identity_verified_enabled' => true,
                    'subscriptions_verification_info_verified_since_enabled' => true,
                    'highlights_tweets_tab_ui_enabled' => true,
                    'responsive_web_twitter_article_notes_tab_enabled' => true,
                    'creator_subscriptions_tweet_preview_api_enabled' => true,
                    'responsive_web_graphql_skip_user_profile_image_extensions_enabled' => false,
                    'responsive_web_graphql_timeline_navigation_enabled' => true,
                ]),
                'fieldToggles' => json_encode([
                    'withAuxiliaryUserLabels' => false,
                ]),
            ],
        ]);
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);
        $typename = $data['data']['user']['result']['__typename'];
        $restId = $data['data']['user']['result']['rest_id'] ?? null;;
        $name = $data['data']['user']['result']['legacy']['name'] ?? null;
        $profileInterstitialType = $data['data']['user']['result']['legacy']['profile_interstitial_type']?? null;
        return response()->json([
            'name' => $name,
            'rest_id' => $restId,
            '__typename' => $typename,
            'profile_interstitial_type' => $profileInterstitialType
        ]);
    }

    public function x_guest_token(){
        $client = new Client();
        $headerAccess = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, như Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'X-Twitter-Active-User' => 'yes',
            'X-Twitter-Client-Language' => 'en'
        ];
        $options = [
            'headers' => $headerAccess
        ];

        $response = $client->request('POST', 'https://api.twitter.com/1.1/guest/activate.json', $options);
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);
        return $data;
    }

    public function testt(Request $request)
    {
        $username = $request->input('username');
        $guestToken = $request->input('guestToken');

        $client = new Client([
            'base_uri' => 'https://api.x.com',
            'timeout'  => 10.0,
            'headers'  => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
                'Cookie' => "gt={$guestToken}",
                'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
                'X-Guest-Token' => $guestToken,
            ],
        ]);
        $response = $client->get("https://api.x.com/graphql/qW5u-DAuXpMEG0zA1F7UGQ/UserByScreenName", [
            'query' => [
                'variables' => json_encode([
                    'screen_name' => $username,
                    'withSafetyModeUserFields' => true,
                ]),
                'features' => json_encode([
                    'hidden_profile_likes_enabled' => true,
                    'hidden_profile_subscriptions_enabled' => true,
                    'rweb_tipjar_consumption_enabled' => true,
                    'responsive_web_graphql_exclude_directive_enabled' => true,
                    'verified_phone_label_enabled' => false,
                    'subscriptions_verification_info_is_identity_verified_enabled' => true,
                    'subscriptions_verification_info_verified_since_enabled' => true,
                    'highlights_tweets_tab_ui_enabled' => true,
                    'responsive_web_twitter_article_notes_tab_enabled' => true,
                    'creator_subscriptions_tweet_preview_api_enabled' => true,
                    'responsive_web_graphql_skip_user_profile_image_extensions_enabled' => false,
                    'responsive_web_graphql_timeline_navigation_enabled' => true,
                ]),
                'fieldToggles' => json_encode([
                    'withAuxiliaryUserLabels' => false,
                ]),
            ],
        ]);
        $result = $response->getBody()->getContents();

        $data = json_decode($result, true);

        $typename = $data['data']['user']['result']['__typename'];
        $restId = $data['data']['user']['result']['rest_id'] ?? null;;
        $name = $data['data']['user']['result']['legacy']['name'] ?? null;
        $profileInterstitialType = $data['data']['user']['result']['legacy']['profile_interstitial_type']?? null;
        
        return response()->json([
            'name' => $name,
            'rest_id' => $restId,
            '__typename' => $typename,
            'profile_interstitial_type' => $profileInterstitialType
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

    function updateCookies($cookies,$cookieJar)
    {
        foreach ($cookieJar->toArray() as $cookie) {
            if ($this->doesCookieExist($cookie['Name'],$cookies)) {
              $this->setCookieValue($cookies,$cookie['Name'],$cookie['Value']);
            }
        }

        $this->account->cookies = $cookies;
        $this->account->save();

    }



    function doesCookieExist($cookieName, $cookies)
    {
        // Tách chuỗi cookies thành các phần tử riêng lẻ
        $cookiePairs = explode('; ', $cookies);

        // Duyệt qua từng phần tử để kiểm tra
        foreach ($cookiePairs as $cookiePair) {
            // Nếu phần tử chứa dấu '=' thì chia tách phần tử thành name và value
            if (strpos($cookiePair, '=') !== false) {
                list($name, $value) = explode('=', $cookiePair, 2);

                // Kiểm tra nếu tên cookie khớp với tên cần kiểm tra
                if ($name === $cookieName) {
                    return true;
                }
            }
        }

        // Trả về false nếu không tìm thấy cookie
        return false;
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

        $accountID = $request->input('account_id');   
        dd($accountID);
        $test = $this->doesCookieExist("kdt",'gt=1807967887992541447; kdt=ToIBVudIkHZqEqNRgDKGLgNoMbDduzwaUKPOAaO1; att=; twid="u=1709000880245399552"; ct0=b18ee02642492cadea5d055755bd191f; auth_token=a5b31d7ab8a2f501ef5937c51a5b03d1839a98b4;	');
        
        dd($test);
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
        $postID = $request->input('postid');
        $accountID = $request->input('accounts');   
        $this->account = Account::find($accountID);
        
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
        $result =  $this->sendRequest('POST', 'https://x.com/i/api/graphql/lI07N6Otwv1PhnEgXILM7A/FavoriteTweet', $headers, $json);
        $this->updateCookies($cookies,$this->jar);
        return $result ;
        // dd($result,$this->jar);

    }

    public function CommentPost(Request $request)
    {
        $postID = $request->input('postid');
        $accountID = $request->input('accounts');   
        $this->account = Account::find($accountID);
        $cookies = $this->account->cookies;
        $headers = [
            'authority' => 'twitter.com',
            'method' => 'POST',
            'path' => '/i/api/graphql/oB-5XsHNAbjvARJEc8CZFw/CreateTweet',
            'scheme' => 'https',
            'accept' => '*/*',
            'accept-encoding' => 'gzip, deflate, br, zstd',
            'accept-language' => 'en-US,en;q=0.9',
            'authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
            'content-length' => '1322',
            'content-type' => 'application/json',
            'cookie' => $cookies,
            'origin' => 'https://x.com',
            'referer' => 'https://x.com/home',
            'x-csrf-token' => $this->getCookieValue($cookies, 'ct0'),
            'x-twitter-active-user' => 'yes',
            'x-twitter-auth-type' => 'OAuth2Session',
            'x-twitter-client-language' => 'en',
        ];

        $json = json_encode([
            
                "variables" => [
                    "tweet_text"=> "Chin Chao",
                    "reply"=>[
                        "in_reply_to_tweet_id"=>"1806502616354238833",
                        "exclude_reply_user_ids"=> []
                    ],
                    "dark_request"=> false,
                    "media"=>[
                        "media_entities"=> [],
                        "possibly_sensitive"=> false

                    ]
                       
                    ,
                    "semantic_annotation_ids"=> []
                ],
                "features"=> [
                    "communities_web_enable_tweet_community_results_fetch"=>  true,
                    "c9s_tweet_anatomy_moderator_badge_enabled"=>  true,
                    "tweetypie_unmention_optimization_enabled"=>  true,
                    "responsive_web_edit_tweet_api_enabled"=>  true,
                    "graphql_is_translatable_rweb_tweet_is_translatable_enabled"=>  true,
                    "view_counts_everywhere_api_enabled"=> true,
                    "longform_notetweets_consumption_enabled"=>  true,
                    "responsive_web_twitter_article_tweet_consumption_enabled"=>  true,
                    "tweet_awards_web_tipping_enabled"=>  false,
                    "creator_subscriptions_quote_tweet_preview_enabled"=>  false,
                    "longform_notetweets_rich_text_read_enabled"=> true,
                    "longform_notetweets_inline_media_enabled"=>  true,
                    "articles_preview_enabled"=>  true,
                    "rweb_video_timestamps_enabled"=>  true,
                    "rweb_tipjar_consumption_enabled"=>  true,
                    "responsive_web_graphql_exclude_directive_enabled"=>  true,
                    "verified_phone_label_enabled"=>  false,
                    "freedom_of_speech_not_reach_fetch_enabled"=>  true,
                    "standardized_nudges_misinfo"=>  true,
                    "tweet_with_visibility_results_prefer_gql_limited_actions_policy_enabled"=> true,
                    "responsive_web_graphql_skip_user_profile_image_extensions_enabled"=>  false,
                    "responsive_web_graphql_timeline_navigation_enabled"=>  true,
                    "responsive_web_enhance_cards_enabled"=>  false
                ]
                    
                ,
                "queryId"=>  "oB-5XsHNAbjvARJEc8CZFw"
        ]);

        // dd($json);
        $result =  $this->sendRequest('POST', 'https://x.com/i/api/graphql/oB-5XsHNAbjvARJEc8CZFw/CreateTweet', $headers, $json);
        return $result ;

    }

    
    public function LoginAccount(Request $request)
    {

        // $listAccount = $request->selected_accounts;
        // dd($listAccount);

        $postID = $request->input('accounts');
        $output = '';
        $this->account = Account::find($postID );
        // dd($this->account);
        $result = $this->initGuestToken();
        $response = json_decode($result['response'], true);
        if ($result['status'] !== 200) {
            return $response['errors'][0]['message'] ?? 'Error';
        }


        $this->guestToken = $response['guest_token'];
        $this->headerCookies .= "gt={$this->guestToken}; ";

        // dd($this->jar,$response);
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
        // $cookiesArray = $this->jar->toArray();
        // $cookiesJson = json_encode($cookiesArray);
        // dd($cookiesArray);
        // $this->unlock();
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
        $cookiesString = 'gt=1808780819592589366; kdt=TpGwQ1udyIlYkrShJiONEGxLTPeQJ0QndIndZRRA; att=; twid="u=1649500285784723457"; ct0=261918d54ecee8e202cecb0008c61a0a; auth_token=36fa68bdf204a178f08418ac9bce47e2e8a57b48; ';
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

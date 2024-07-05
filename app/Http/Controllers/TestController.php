<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestController extends Controller
{
    public function testProxy()
    {
        $client = new Client([]);

        try {
            $response = $client->request('GET', 'http://ip-api.com/json');
            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Request failed: ' . $e->getMessage()], 500);
        }
    }
    // public function login(Request $request)
    // {
    //     // Validate the form data
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     // Attempt to log the user in
    //     if (Auth::attempt($credentials)) {
    //         // Authentication was successful...
    //         return redirect()->intended('login'); // Redirect to a specified path after successful login
    //     }

    //     // Authentication failed...
    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    /**
     * Display a listing of the resource.
     */


     public function runPythonScript()
     {
         // Đường dẫn tới file Python trong thư mục public
         // Chạy file Python
         $output = shell_exec("python test.py");
 
         // Kiểm tra kết quả đầu ra
         if ($output === null) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Failed to run Python script or no output received.'
             ]);
         }
 
         // Giả sử output là JSON
         $result = json_decode($output, true);
 
         if ($result === null) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Failed to decode JSON or script output is not JSON.',
                 'output' => $output
             ]);
         }
 
         return response()->json([
             'status' => 'success',
             'data' => $result
         ]);
     }

    public function x_guest_token()
    {
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
    public function CheckAccount(Request $request)
    {
        //x_guest_token();
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
        $profileInterstitialType = $data['data']['user']['result']['legacy']['profile_interstitial_type'] ?? null;

        return response()->json([
            'name' => $name,
            'rest_id' => $restId,
            '__typename' => $typename,
            'profile_interstitial_type' => $profileInterstitialType
        ]);
    }

    public function index()
    {
        //
        return view('login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo 'create';
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        echo 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        echo 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        echo 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        echo 'update';
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        echo 'destroy';
        //
    }
}

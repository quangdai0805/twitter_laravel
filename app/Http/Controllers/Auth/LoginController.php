<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Flasher\Prime\FlasherInterface;
use App\Models\User;

class LoginController extends Controller
{
    //
    
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


    public function showLoginForm()
    {
        if(Auth::id()>0){
            return redirect()->route('main')->with('success', 'Đăng nhập thành công!');
        }
        return view('login');
    }
    
    public function login(Request $request, FlasherInterface $flasher)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            toastr()->success('Đăng nhập thành công!');
            return redirect()->route('main')->with([
                'username' => $user->name
            ]);
        } else {
            toastr()->error('Đăng nhập thất bại!');
            return redirect()->route('login');
        }
    }

    public function register(Request $request, FlasherInterface $flasher)
    {
        
        // Validate the form data
        $credentials = $request->validate([
            // 'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // toastr()->error('Tai khoan da ton tai');
             dd($user);
            // return redirect()->route('register')->with([
            //     'username' => $user->name
            // ]);
        } else {

            $name = $request->input('name');
            User::factory()->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
            toastr()->success('Đăng ky thanh cong!');
            return redirect()->route('login');
        }
    }

    // Endpoint xử lý từng số
    public function showMainView()
    {
        return view('main');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}

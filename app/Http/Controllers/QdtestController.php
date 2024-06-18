<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QdtestController extends Controller
{
    //
    public function qd(){
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, như Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA',
                'X-Twitter-Active-User' => 'yes',
                'X-Twitter-Client-Language' => 'en',
            ])->post('https://api.x.com/1.1/guest/activate.json', 
                // [
                //     'param1' => 'value1',
                //     'param2' => 'value2',
                //     // thêm các tham số khác ở đây
                // ]
            );

            if ($response->successful()) {
                $result = $response->body();
                return view('show-data', compact('result'));
            } else {
                return response()->json(['error' => 'Request failed with status ' . $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

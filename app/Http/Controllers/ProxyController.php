<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    //
    public function checkProxyaskjda(){

       
    }
    public function checkProxy(Request $request)
    {
        $data = $request->all();
        $proxy = $data['proxy'];

        // Regular expression to check proxy format
        $pattern = '/^(?:(https?|socks5):\/\/)?(?:[a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?(\d{1,3}\.){3}\d{1,3}:\d+$/';
        if (!preg_match($pattern, $proxy)) {
            return response()->json(['error' => 'Invalid proxy format.'], 400);
        }

        // Check if the proxy string includes a protocol, if not default to http
        if (!preg_match('/^(https?|socks5):\/\//', $proxy)) {
            $proxy = 'http://' . $proxy;
        }

        try {
            $client = new Client();

            $response = $client->request('POST', 'http://ip-api.com/json', [
                'proxy' => $proxy,
                'timeout' => 10, // Optional: Set a timeout for the request
            ]);

            return response()->json([
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // // dd($proxy);

        // // Regular expression to check proxy format
        // // $pattern = '/^(?:[a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?(\d{1,3}\.){3}\d{1,3}:\d+$/';
        // // if (!preg_match($pattern, $proxy)) {
        // //     return response()->json(['error' => 'Invalid proxy format.'], 400);
        // // }

        // // Extract IP and port for connection test
        // // $proxy_parts = parse_url('http://' . $proxy);
        // // $proxy_ip = $proxy_parts['host'];
        // // $proxy_port = $proxy_parts['port'];

        // $client =  new Client([
        //     'base_uri' => 'https://api.x.com',
        //     'timeout'  => 10.0,
        // ]);

        // try {
         
        //     $response = $client->request('POST', 'http://ip-api.com/json', [

        //         'proxy' => "https://$proxy",
        //         // 'proxy' => [
        //         //     // 'http'  => "http://$proxy", // Proxy HTTP
        //         //     'https' => "https://$proxy", // Proxy HTTPS (nếu cần)
        //         // ],

        //     ]);
        //     $responseBody = $response->getBody()->getContents();
        //     return [
        //         'status' => $response->getStatusCode(),
        //         'response' => $responseBody,
        //         'headers' => $response->getHeaders()
        //     ];
        // } catch (\Exception $ex) {
        //     return $ex->getMessage();
        // }
       

        
        // Check connection to the proxy
        // $connection = @fsockopen($proxy_ip, $proxy_port, $errno, $errstr, 5);

        // if (!$connection) {
        //     return response()->json(['error' => 'Cannot connect to proxy.'], 400);
        // }

        // fclose($connection);
        // return response()->json(['success' => 'Proxy is valid and reachable.'], 200);
    }
}

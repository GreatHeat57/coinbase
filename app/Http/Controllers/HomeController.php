<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client as GuzzleClient;

use App\Models\Asset;

class HomeController extends Controller
{
    public function index()
    {
        Asset::query()->delete();

        $httpClient = new GuzzleClient([
            'headers' => [
                'X-CoinAPI-Key' => env('COIN_API_KEY')
            ]
        ]);

        $response = $httpClient->get(env('COIN_API'));

        $data = json_decode($response->getBody()->getContents());
        
        $assets = [];
        foreach ($data as $value) {
            $assets[] = [
                'asset_id' => $value->asset_id,
                'name' => $value->name,
                'price_usd' => isset($value->price_usd) ? $value->price_usd : 1,
                'data_start' => isset($value->data_start) ? $value->data_start : "",
                'data_end' => isset($value->data_end) ? $value->data_end : ""
            ];
        }

        foreach (array_chunk($assets, 1000) as $asset)  
        {
            Asset::insert($asset);
        }
        
        return view('home');
    }
}

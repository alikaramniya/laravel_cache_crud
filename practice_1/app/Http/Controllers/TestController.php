<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    /**
     * index method
     */
    public function index()
    {
        $arr = [
            ['id' => 1, 'name' => 'Ali'],
            ['id' => 2, 'name' => 'Hamed'],
            ['id' => 3, 'name' => 'Jasem'],
            ['id' => 4, 'name' => 'Ghasem']
        ];

        Cache::forget('arr');

        Cache::put('arr', collect($arr), 100000);

        $arr = Cache::get('arr');

        $newArr = $arr->firstWhere(function ($item) {
            return $item['id'] === 2;
        });

        dd($arr->forget($newArr));

        Cache::forever('arr', collect($newArr));
    }
}

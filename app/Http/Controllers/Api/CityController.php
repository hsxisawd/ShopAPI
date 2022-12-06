<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;


class CityController extends BaseController
{
    public function index(Request $request)
    {
        return city_cache($request->input('pid',0));
    }
}

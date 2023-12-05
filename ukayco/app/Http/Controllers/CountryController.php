<?php

namespace App\Http\Controllers;
use App\Models\Country;

use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Get all countries.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $countries = Country::all();
        return response()->json($countries);
    }
}

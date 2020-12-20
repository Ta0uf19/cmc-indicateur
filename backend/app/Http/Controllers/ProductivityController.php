<?php


namespace App\Http\Controllers;


use App\Models\ProductivityAnalytics;

class ProductivityController extends Controller
{


    /***
     * Retrieve productivity
     */
    public function get() {
        return ProductivityAnalytics::all();
    }
}

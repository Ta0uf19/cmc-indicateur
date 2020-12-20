<?php


namespace App\Http\Controllers;


use App\Models\ConfidenceAnalytics;
use App\Models\ProductivityAnalytics;
use App\Models\Transition;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{


    /***
     * Retrieve productivity
     */
    public function get() {
        return ProductivityAnalytics::all();
    }

    /**
     * Confidence
     */
    public function getConfidence() {

        $confidence_users = ConfidenceAnalytics::all();
        $count = $confidence_users->count();
        $total = 0;
        foreach ($confidence_users as $user) {
            $total += $user->total;
        }
        $avg = number_format($total/$count,2);

        return response()->json(["data" => $confidence_users->toArray(), "avg_confidence" => $avg]);
    }
}

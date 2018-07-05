<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class StatisticController extends Controller
{
    public function calculateCommonRates() {
        $user = Auth::user();

        $stats = [
            'current_holdings' => $user->tokens_count,
            'market_value' => 10500000,
            'market_price' => 11.22,
            'net_asset_value' => 8.88,
            'portfolio_yield' => 55.05
        ];
        return response()->json(['success' => 'ok', 'record' => $stats], $this->sucessStatus);
    }
}

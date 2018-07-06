<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function calculateCommonRates() {
        $holdings = DB::table('assets')->sum('holding');
        $marketPrice = env('MARKET_PRICE');
        $marketValue = $holdings * $marketPrice;
        $netAssetValue = env('NET_ASSET_VALUE');
        $portfolioYield = $this->calculatePortfolioYield();

        $stats = [
            'current_holdings' => $holdings,
            'market_value' => $marketValue,
            'market_price' => $marketPrice,
            'net_asset_value' => $netAssetValue,
            'portfolio_yield' => $portfolioYield
        ];
        return response()->json(['success' => 'ok', 'record' => $stats], $this->sucessStatus);
    }

    public function calculatePortfolioYield() {
        $counter = 0;
        $portfolioYield = 0;
        $holdings = DB::table('assets')->sum('holding');

        $percents = DB::table('assets')
            ->select(DB::raw("type_id, round(sum(`holding`) * 100 / {$holdings}, 2) as holdings"))
            ->groupBy('type_id')
            ->get();

        foreach ($percents as $percent) {
            $counter++;
            $portfolioYield += $percent->holdings;
        }

        $portfolioYield = $portfolioYield / $counter;
        return $portfolioYield;
    }

    public function calculateAssetsGroupCommonValues() {
        $marketPrice = env('MARKET_PRICE');

        $holdings = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id, (sum(`holding`) * {$marketPrice}) / 1000000 as value"))
            ->groupBy('types.name', 'type_id')
            ->get();

        return $holdings;
    }
}

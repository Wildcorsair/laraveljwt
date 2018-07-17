<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Price;

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

        $portfolioYield = round($portfolioYield / $counter, 1);
        return $portfolioYield;
    }

    public function calculateAssetsGroupCommonValues() {
        $marketPrice = env('MARKET_PRICE');

        $holdings = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id, round((sum(`holding`) * {$marketPrice}) / 10000, 2) as value"))
            ->groupBy('types.name', 'type_id')
            ->orderBy('assets.type_id')
            ->get();

        return $holdings;
    }

    public function calculateAssetsGroupCommonValuesByUser() {
        $user = Auth::user();
        $tokensCount = $user->tokens_count;
        $holdings = DB::table('assets')->sum('holding');
        $userPercent = $tokensCount * 100 / $holdings;

        $holdings = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id, round((sum(`holding`) * {$userPercent} / 100), 2) as value"))
            ->groupBy('types.name', 'type_id')
            ->orderBy('assets.type_id')
            ->get();

        return $holdings;
    }

    public function calculatePortfolioYieldsByCountries() {
        $holdings = DB::table('assets')->sum('holding');

        $percents = DB::table('assets')
            ->leftJoin('countries', 'assets.country_id', '=', 'countries.id')
            ->select(DB::raw("countries.name, country_id, round(sum(`holding`) * 100 / {$holdings}, 2) as value"))
            ->groupBy('countries.name', 'country_id')
            ->orderBy('countries.name')
            ->get();

        return $percents;
    }

    public function calculatePortfolioYieldsByAssets() {
        $holdings = DB::table('assets')->sum('holding');

        $percents = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id, round(sum(`holding`) * 100 / {$holdings}, 2) as value"))
            ->groupBy('types.name', 'type_id')
            ->orderBy('type_id')
            ->get();

        return $percents;
    }

    public function calculatePortfolioYieldsBySectors() {
        $holdings = DB::table('assets')->sum('holding');

        $percents = DB::table('assets')
            ->leftJoin('sectors', 'assets.sector_id', '=', 'sectors.id')
            ->select(DB::raw("sectors.name, sector_id, round(sum(`holding`) / {$holdings}, 2) as value"))
            ->groupBy('sectors.name', 'sector_id')
            ->orderBy('sector_id')
            ->get();

        return $percents;
    }

    public function getPricesStatistic(Request $request) {
        $startDate = date("Y-m-d", strtotime($request->start));
        $endDate = date("Y-m-d", strtotime($request->end));

        $prices = Price::where('grip_date', '>=', $startDate)
            ->where('grip_date', '<', $endDate)
            ->get();

        if ($request->period !== '') {
            $i = 1;
            if ($request->period == 'Quarterly') {
                while ($i < count($prices)) {
                    $result[] = $prices[$i];
                    $i = $i + 3;
                }
                $prices = $result;
            } else if ($request->period == 'Yearly') {
                while ($i < count($prices)) {
                    $result[] = $prices[$i];
                    $i = $i + 12;
                }
                $prices = $result;
            } else if ($request->period == 'Lifetime') {
                while ($i < count($prices)) {
                    $result[] = $prices[$i];
                    $i = $i + 61;
                }
                $prices = $result;
            }
        }

        // $prices = DB::table('prices')->select(DB::raw('grip_date, SUM(`price`) AS `price`, SUM(`volume`) AS `volume`'))->where('grip_date', '>=', $startDate)
        //     ->where('grip_date', '<', $endDate)
        //     ->groupBy('grip_date')
        //     ->get();

        return response()->json(['success' => 'ok', 'dataset' => $prices], $this->sucessStatus);
    }

    public function getDashboardStatistic() {
        // Calculate stats for 'ASSET CLASS' (Chart #1)
        $holdings = $this->calculateAssetsGroupCommonValues();
        // Calculate stats for 'ASSET CLASS' by customer (Chart #1)
        $holdingByUser = $this->calculateAssetsGroupCommonValuesByUser();
        // Calculate stats for 'GEOGRAPHICAL EXPOSURE' data (Graph #1)
        $percentsByCountries = $this->calculatePortfolioYieldsByCountries();
        // Calculate stats for 'ASSET CLASS YIELDS (% P.A.)' data (Graph #2)
        $percentsByAssets = $this->calculatePortfolioYieldsByAssets();
        // Calculate stats for 'ECONOMIC SECTOR WIDE PORTFOLIO EXPOSURE' (Graph3)
        $percentsBySectors = $this->calculatePortfolioYieldsBySectors();

        return response()->json([
            'success' => 'ok',
            'dataset' => [
                'asset_class' => $holdings,
                'asset_class_user' => $holdingByUser,
                'geographical_exposure' => $percentsByCountries,
                'asset_class_yields' => $percentsByAssets,
                'geographical_exposure_sector' => $percentsBySectors
            ]
        ], $this->sucessStatus);
    }
}

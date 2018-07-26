<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Price;
use App\Type;
use App\Country;
use App\Sector;

class StatisticController extends Controller
{
    public function calculateHoldings() {
        $holdings = DB::table('assets')->sum('holding');
        return $holdings;
    }

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

    public function calculateAssetsGroupCommonValuesAll() {
        $holdings = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id, round((sum(`holding`)), 2) as value"))
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

        // Field set: "name", "item_id", "value"
        // Using in the "horizontal" chart
        $percents = DB::table('assets')
            ->leftJoin('types', 'assets.type_id', '=', 'types.id')
            ->select(DB::raw("types.name, type_id AS item_id, round(sum(`holding`) * 100 / {$holdings}, 2) as value"))
            ->groupBy('types.name', 'type_id')
            ->orderBy('type_id')
            ->get();

        return $percents;
    }

    public function calculatePortfolioYieldsBySectors() {
        $holdings = DB::table('assets')->sum('holding');

        // Field set: "name", "item_id", "value"
        // Using in the "horizontal" chart
        $percents = DB::table('assets')
            ->leftJoin('sectors', 'assets.sector_id', '=', 'sectors.id')
            ->select(DB::raw("sectors.name, sector_id AS item_id, round(sum(`holding`) / {$holdings}, 2) as value"))
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

    public function getPricesForMonthBegin() {
        // $currentMonth = date('Y-m') . '-01';
        $currentMonth = date('Y-m-d', strtotime("first day of this month"));
        $dates[] = $currentMonth;

        for ($i = 1; $i < 6; $i++) {
            $dates[] = date('Y-m-01', strtotime("-{$i} month"));
        }

        $prices = Price::whereIn('grip_date', $dates)->get();

        return $prices;
    }

    public function getDashboardStatistic() {

        $holdingsCirculation = $this->calculateHoldings();
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

        $monthPrices = $this->getPricesForMonthBegin();

        return response()->json([
            'success' => 'ok',
            'dataset' => [
                'holdings' => $holdingsCirculation,
                'asset_class' => $holdings,
                'asset_class_user' => $holdingByUser,
                'geographical_exposure' => $percentsByCountries,
                'asset_class_yields' => $percentsByAssets,
                'geographical_exposure_sector' => $percentsBySectors,
                'month_prices' => $monthPrices
            ]
        ], $this->sucessStatus);
    }

    public function getDashboardCommonStatistic() {

        $holdingsCirculation = $this->calculateHoldings();
        // Calculate stats for 'ASSET CLASS' (Chart #1)
        $holdings = $this->calculateAssetsGroupCommonValues();
        // Calculate stats for 'ASSET CLASS' by customer (Chart #1)
        $holdingByUser = $this->calculateAssetsGroupCommonValuesAll();
        // Calculate stats for 'GEOGRAPHICAL EXPOSURE' data (Graph #1)
        $percentsByCountries = $this->calculatePortfolioYieldsByCountries();
        // Calculate stats for 'ASSET CLASS YIELDS (% P.A.)' data (Graph #2)
        $percentsByAssets = $this->calculatePortfolioYieldsByAssets();
        // Calculate stats for 'ECONOMIC SECTOR WIDE PORTFOLIO EXPOSURE' (Graph3)
        $percentsBySectors = $this->calculatePortfolioYieldsBySectors();

        $monthPrices = $this->getPricesForMonthBegin();

        return response()->json([
            'success' => 'ok',
            'dataset' => [
                'holdings' => $holdingsCirculation,
                'asset_class' => $holdings,
                'asset_class_user' => $holdingByUser,
                'geographical_exposure' => $percentsByCountries,
                'asset_class_yields' => $percentsByAssets,
                'geographical_exposure_sector' => $percentsBySectors,
                'month_prices' => $monthPrices
            ]
        ], $this->sucessStatus);
    }

    public function getAssetClassStats(Request $request) {
        if (!$request->type || !is_numeric($request->type)) {
            return response()->json(['success' => 'error', 'message' => 'No require parameter'], $this->badRequest);
        }

        $typeId = (int)$request->type;

        $assetClass = Type::find($typeId);

        $holdings = DB::table('assets')->sum('holding');

        $statsData = DB::table('assets')
            ->leftJoin('sectors', 'assets.sector_id', '=', 'sectors.id')
            ->leftJoin('countries', 'assets.country_id', '=', 'countries.id')
            ->select(DB::raw('assets.name, countries.code AS code, sectors.name AS sector, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('type_id', $typeId)
            ->groupBy('countries.code', 'sectors.name', 'assets.name')
            ->get();


        $common = DB::table('assets')
            ->select(DB::raw('COUNT(assets.country_id) AS country_count, COUNT(assets.sector_id) AS sector_count, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('type_id', $typeId)
            ->first();

        return response()->json(['success' => 'ok', 'dataset' => ['type' => $assetClass, 'data' => $statsData, 'common' => $common]], $this->sucessStatus);
    }

    public function getGeoStatistic(Request $request) {
        if (!$request->country) {
            return response()->json(['success' => 'error', 'message' => 'No require parameter'], $this->badRequest);
        }

        $countryId = (int)$request->country;

        $country = Country::find($countryId);

        $holdings = DB::table('assets')->sum('holding');

        $statsData = DB::table('assets')
            ->leftJoin('sectors', 'assets.sector_id', '=', 'sectors.id')
            ->leftJoin('countries', 'assets.country_id', '=', 'countries.id')
            ->select(DB::raw('assets.name, countries.code AS code, sectors.name AS sector, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('country_id', $countryId)
            ->groupBy('countries.code', 'sectors.name', 'assets.name')
            ->get();


        $common = DB::table('assets')
            ->select(DB::raw('COUNT(assets.country_id) AS country_count, COUNT(assets.sector_id) AS sector_count, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('country_id', $countryId)
            ->first();

        return response()->json(['success' => 'ok', 'dataset' => ['type' => $country, 'data' => $statsData, 'common' => $common]], $this->sucessStatus);
    }

    public function getSectorStatistic(Request $request) {
        if (!$request->sector) {
            return response()->json(['success' => 'error', 'message' => 'No require parameter'], $this->badRequest);
        }

        $sectorId = (int)$request->sector;

        $sector = Sector::find($sectorId);

        $holdings = DB::table('assets')->sum('holding');

        // Calculate separated statistic
        $statsData = DB::table('assets')
            ->leftJoin('sectors', 'assets.sector_id', '=', 'sectors.id')
            ->leftJoin('countries', 'assets.country_id', '=', 'countries.id')
            ->select(DB::raw('assets.name, countries.code AS code, sectors.name AS sector, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('sector_id', $sectorId)
            ->groupBy('countries.code', 'sectors.name', 'assets.name')
            ->get();

        // Calculate total statistic
        $common = DB::table('assets')
            ->select(DB::raw('COUNT(assets.country_id) AS country_count, COUNT(assets.sector_id) AS sector_count, SUM(holding) AS holding, ROUND(SUM(holding) * ' . env('MARKET_PRICE') . ', 2) AS market_value, round(sum(`holding`) * 100 / ' . $holdings . ', 2) as portfolio'))
            ->where('sector_id', $sectorId)
            ->first();

        return response()->json(['success' => 'ok', 'dataset' => ['type' => $sector, 'data' => $statsData, 'common' => $common]], $this->sucessStatus);
    }
}

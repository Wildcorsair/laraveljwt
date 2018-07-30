<?php

namespace App\Http\Controllers\API;

use App\Asset;
use App\Type;
use App\Sector;
use App\TradingBlock;
use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    /**
     * Return list of assets.
     */
    public function index(Request $request) {
        $user = Auth::user();
        if (!$user->can('asset-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $type = $request->query('type');

        $assets = Asset::where('type_id', $type)
            ->with('country')
            ->with('sector')
            ->with('trading_block')
            ->with('type')
            ->paginate(5);
        return response()->json(['success' => 'ok', 'paginator' => $assets], $this->sucessStatus);
    }

    /**
     * Store a new asset.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $user = Auth::user();
        if (!$user->can('asset-create')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $this->validate($request, [
            'name' => 'required',
            'ticker' => 'required',
            'holding' => 'required',
            'marketValue' => 'required',
            'delta' => 'required',
            'profit' => 'required',
            'returnCurrency' => 'required',
            'returnPercent' => 'required',
            'typeId' => 'required',
            'sectorId' => 'required',
            'tradingBlockId' => 'required',
            'countryId' => 'required'
        ]);

        $type = Type::find($request->get('typeId'));
        $sector = Sector::find($request->get('sectorId'));
        $tradingBlock = TradingBlock::find($request->get('tradingBlockId'));
        $country = Country::find($request->get('countryId'));

        $asset = new Asset();
        $asset->name = $request->get('name');
        $asset->ticker = $request->get('ticker');
        $asset->holding = $request->get('holding');
        $asset->market_value = $request->get('marketValue');
        $asset->delta = $request->get('delta');
        $asset->profit = $request->get('profit');
        $asset->return_currency = $request->get('returnCurrency');
        $asset->return_percent = $request->get('returnPercent');

        $asset->type()->associate($type);
        $asset->sector()->associate($sector);
        $asset->trading_block()->associate($tradingBlock);
        $asset->country()->associate($country);
        $asset->save();

        return response()->json(['success' => 'created', 'record' => $asset], $this->sucessStatus);
    }

    public function edit($id) {
        $user = Auth::user();
        if (!$user->can('asset-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $asset = Asset::find($id);
        return response()->json(['success' => 'ok', 'record' => $asset], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if (!$user->can('asset-update')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $this->validate($request, [
            'name' => 'required',
            'ticker' => 'required',
            'holding' => 'required',
            'marketValue' => 'required',
            'delta' => 'required',
            'profit' => 'required',
            'returnCurrency' => 'required',
            'returnPercent' => 'required',
            'typeId' => 'required',
            'sectorId' => 'required',
            'tradingBlockId' => 'required',
            'countryId' => 'required'
        ]);

        $type = Type::find($request->get('typeId'));
        $sector = Sector::find($request->get('sectorId'));
        $tradingBlock = TradingBlock::find($request->get('tradingBlockId'));
        $country = Country::find($request->get('countryId'));

        $asset = Asset::find($id);
        $asset->name = $request->get('name');
        $asset->ticker = $request->get('ticker');
        $asset->holding = $request->get('holding');
        $asset->market_value = $request->get('marketValue');
        $asset->delta = $request->get('delta');
        $asset->profit = $request->get('profit');
        $asset->return_currency = $request->get('returnCurrency');
        $asset->return_percent = $request->get('returnPercent');

        $asset->type()->associate($type);
        $asset->sector()->associate($sector);
        $asset->trading_block()->associate($tradingBlock);
        $asset->country()->associate($country);
        $asset->save();

        return response()->json(['success' => 'updated', 'record' => $asset], $this->sucessStatus);
    }

    public function destroy($id) {
        $user = Auth::user();
        if (!$user->can('asset-delete')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $equity = Asset::find($id);
        $equity->delete();

        return response()->json(['success' => 'deleted'], $this->sucessStatus);
    }
}

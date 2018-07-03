<?php

namespace App\Http\Controllers\API;

use App\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    /**
     * Return list of assets.
     */
    public function index() {
        $assets = Asset::where('type', 'EQUITIES')
            ->with('country')
            ->with('sector')
            ->with('trading_block')
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
/*        $user = Auth::user();
        if (!$user->can('asset-create')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }*/

        $this->validate($request, [
            'type' => 'required',
            'name' => 'required',
            'holding' => 'required',
            'marketValue' => 'required',
            'profit' => 'required',
            'sectorId' => 'required',
            'tradingBlockId' => 'required',
            'countryId' => 'required'
        ]);

        $asset = new Asset();
        $asset->type = $request->get('type');
        $asset->name = $request->get('name');
        $asset->holding = $request->get('holding');
        $asset->market_value = $request->get('marketValue');
        $asset->profit = $request->get('profit');
        $asset->sector_id = $request->get('sectorId');
        $asset->trading_block_id = $request->get('tradingBlockId');
        $asset->country_id = $request->get('countryId');
        $asset->save();

        return response()->json(['success' => 'created', 'record' => $asset], $this->sucessStatus);
    }

    public function edit($id) {
        $asset = Asset::find($id);
        return response()->json(['success' => 'ok', 'record' => $asset], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'type' => 'required',
            'name' => 'required',
            'holding' => 'required',
            'marketValue' => 'required',
            'profit' => 'required',
            'sectorId' => 'required',
            'tradingBlockId' => 'required',
            'countryId' => 'required'
        ]);

        $asset = Asset::find($id);
        $asset->type = $request->get('type');
        $asset->name = $request->get('name');
        $asset->holding = $request->get('holding');
        $asset->market_value = $request->get('marketValue');
        $asset->profit = $request->get('profit');
        $asset->sector_id = $request->get('sectorId');
        $asset->trading_block_id = $request->get('tradingBlockId');
        $asset->country_id = $request->get('countryId');
        $asset->save();

        return response()->json(['success' => 'updated', 'record' => $asset], $this->sucessStatus);
    }

    public function destroy($id) {
/*        $user = Auth::user();
        if (!$user->can('administrator-delete')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }*/
        $equity = Asset::find($id);
        $equity->delete();

        return response()->json(['success' => 'deleted'], $this->sucessStatus);
    }
}

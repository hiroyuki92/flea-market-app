<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function show($item_id)
{
    $item = Item::findOrFail($item_id);
    $user = auth()->user();
    $shippingAddress = session('shipping_address', [
        'shipping_postal_code' => $user->postal_code,
        'shipping_address_line' => $user->address_line,
        'shipping_building' => $user->building,
    ]);
    return view('confirm', compact('item','shippingAddress'));
}

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('address', compact('item', 'user'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $user = auth()->user();
        $request->session()->put('shipping_address', [
        'shipping_postal_code' => $request->postal_code,
        'shipping_address_line' => $request->address_line,
        'shipping_building' => $request->building,
    ]);
        return redirect()->route('purchase.index', ['item_id' => $item_id]);
    }

}
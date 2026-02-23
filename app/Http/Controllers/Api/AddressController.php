<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
{
    $address = Address::create([
        'user_id' => $request->user()->id,
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'pincode' => $request->pincode
    ]);

    return response()->json(['status'=>true,'address'=>$address]);
}
}

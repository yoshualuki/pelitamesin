<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Session;

class CustomerProfileController extends Controller
{
    protected $rajaOngkirService;

    public function __construct()
    {

        // Initialize RajaOngkir
        $this->rajaOngkirService = new RajaOngkirService(config('rajaongkir.api_key'));
    }

    public function profile()
    {
        $user = Session::get('user');
        $user = User::find($user->id);
        $rajaOngkirProvince = $this->rajaOngkirService->getProvinces();
        $rajaOngkirCity = $this->rajaOngkirService->getCities($user->province_id);
        $provinces = $rajaOngkirProvince['rajaongkir']['results'];
        $cities = $rajaOngkirCity['rajaongkir']['results'];
        app('debugbar')->info($cities);
        return view('customer.profile', compact('user', 'provinces', 'cities',));
    }

    public function updateProfile(Request $request)
    {
        $user = Session::get('user');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|string',
            'province_name' => 'required|string|max:255',
            'city_id' => 'required|string',
            'city_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:500'
        ]);


        $user = User::find($user->id);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'province_id' => $validated['province_id'],
            'province_name' => $validated['province_name'],
            'city_id' => $validated['city_id'],
            'city_name' => $validated['city_name'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
        ]);
    }
}

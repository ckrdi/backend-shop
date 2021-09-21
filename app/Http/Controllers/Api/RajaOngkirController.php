<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Steevenz\Rajaongkir;

class RajaOngkirController extends Controller
{
    protected $rajaongkir;

    public function __construct()
    {
        $this->rajaongkir = new Rajaongkir(config('rajaongkir.API_KEY'), Rajaongkir::ACCOUNT_STARTER);
    }

    public function getProvinces()
    {
        return response()->json([
            'success' => true,
            'message' => 'All provinces',
            'provinces' => $this->rajaongkir->getProvinces()
        ]);
    }

    public function getCities()
    {
        return response()->json([
            'success' => true,
            'message' => 'All cities',
            'cities' => $this->rajaongkir->getCities()
        ]);
    }

    /**
     * --------------------------------------------------------------
     * Mendapatkan harga ongkos kirim berdasarkan berat dalam gram
     *
     * @param array Origin
     * @param array Destination
     * @param int|array Weight|Metrics
     * @param string Courier
     * --------------------------------------------------------------
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCost(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required',
            'courier' => 'required'
        ]);

        $origin = ['city' => $request->origin];
        $destination = ['city' => $request->destination];
        $weight = $request->weight;
        $courier = $request->courier;

        return response()->json([
            'success' => true,
            'message' => 'All costs by courier: '.$request->courier,
            'cost' => $this->rajaongkir->getCost($origin, $destination, $weight, $courier)
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CarManufacturer;
use App\Models\CarModel;
use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FuelTypes extends Controller
{
    public function getAll(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $fuelTypes = FuelType::all();
        return response(['fuelTypes' => $fuelTypes], 200);
    }

    public function newFuelType(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:fuel_types',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        FuelType::create($request->toArray());

        $response = ['result' => true];
        return response($response, 200);

    }

    public function deleteFuelType(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $fuelType = FuelType::where('id', $request['id'])->first();

        if (!$fuelType) {
            $response = ['result' => false, 'message' => 'Fuel type is not found'];
            return response($response, 404);
        }

        $fuelType->delete();

        $response = ['result' => true];
        return response($response, 200);

    }

    public function editFuelType(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|unique:fuel_types',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $fuelType = FuelType::where('id', $request['id'])->first();

        if (!$fuelType) {
            $response = ['result' => false, 'message' => 'Fuel type is not found'];
            return response($response, 404);
        }

        $fuelType->name = $request['name'];
        $fuelType->save();

        $response = ['result' => true];
        return response($response, 200);

    }
}

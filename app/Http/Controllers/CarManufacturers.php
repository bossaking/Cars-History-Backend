<?php

namespace App\Http\Controllers;

use App\Models\CarManufacturer;
use App\Models\Firm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CarManufacturers extends Controller
{
    public function getAll(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $carManufacturers = CarManufacturer::all();

        foreach ($carManufacturers as $manufacturer){
            $modelsCount = count($manufacturer->carModels);
            $manufacturer['modelsCount'] = $modelsCount;
        }

        return response(['carManufacturers' => $carManufacturers], 200);
    }

    public function manufacturerModels(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $manufacturer = CarManufacturer::where('id', $request['id'])->first();

        if(!$manufacturer){
            $response = ['result' => false, 'message' => 'Car manufacturer is not found'];
            return response($response, 404);
        }else{
            $manufacturer['carModels'] = $manufacturer->carModels;
            $response = ['manufacturer' => $manufacturer];
            return response($response, 200);
        }
    }

    public function newManufacturer(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:car_manufacturers',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        CarManufacturer::create($request->toArray());

        $response = ['result' => true];
        return response($response, 200);
    }

    public function deleteManufacturer(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $manufacturer = CarManufacturer::where('id', $request['id'])->first();

        if(!$manufacturer){
            $response = ['result' => false, 'message' => 'Car manufacturer is not found'];
            return response($response, 404);
        }else{

            $manufacturer->delete();

            $response = ['result' => true];
            return response($response, 200);
        }
    }

    public function editManufacturer(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|unique:car_manufacturers',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $manufacturer = CarManufacturer::where('id', $request['id'])->first();

        if(!$manufacturer){
            $response = ['result' => false, 'message' => 'Car manufacturer is not found'];
            return response($response, 404);
        }else{

            $manufacturer->name = $request['name'];
            $manufacturer->save();

            $response = ['result' => true];
            return response($response, 200);
        }
    }

}

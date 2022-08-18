<?php

namespace App\Http\Controllers;

use App\Models\CarManufacturer;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarModels extends Controller
{
    public function getAll(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $models = CarModel::all();
        return response(['models' => $models], 200);
    }

    public function newModel(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'car_manufacturer_id' => 'required|integer',
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $manufacturer = CarManufacturer::where('id', $request['car_manufacturer_id'])->first();
        if (!$manufacturer) {
            $response = ['result' => false, 'message' => 'Car manufacturer is not found'];
            return response($response, 404);
        }

        $model = CarModel::where('car_manufacturer_id', $request['car_manufacturer_id'])->where('name', $request['name'])->first();
        if ($model) {
            $response = ['result' => false, 'message' => 'Model already exist'];
            return response($response, 422);
        }

        CarModel::create($request->toArray());

        $response = ['result' => true];
        return response($response, 200);

    }

    public function deleteModel(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $model = CarModel::where('id', $request['id'])->first();

        if (!$model) {
            $response = ['result' => false, 'message' => 'Car model is not found'];
            return response($response, 404);
        }

        $model->delete();

        $response = ['result' => true];
        return response($response, 200);

    }

    public function editModel(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $model = CarModel::where('id', $request['id'])->first();

        if (!$model) {
            $response = ['result' => false, 'message' => 'Car model is not found'];
            return response($response, 404);
        }

        $anotherModel = CarModel::where('car_manufacturer_id', $model->car_manufacturer_id)->where('name', $request['name'])->first();
        if ($anotherModel) {
            $response = ['result' => false, 'message' => 'Model with same name already exist'];
            return response($response, 422);
        }

        $model->name = $request['name'];
        $model->save();

        $response = ['result' => true];
        return response($response, 200);

    }
}

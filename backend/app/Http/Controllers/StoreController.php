<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function createStore(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'storeName' => 'required|string',
            'clientId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errorMessage' => $validator->errors()
            ], 400);
        }

        //verificar se existe uma loja com o mesmo ID, nome


        $newStoreData = new Store([
            'name' => $data['storeName'],
            'client_id' => $data['clientId'],
        ]);

        $newStoreData->save();

        return response()->json([
            'message' => 'Loja criada com sucesso!',
        ], 200);
    }

    public function getStoreById($userId) {

        $validator = Validator::make(['userId' => $userId], [
            'userId' => 'required|integer',
        ]);

        if($validator->fails()) {
            return response()->json([
                'errorMessage' => $validator->errors()
            ], 400);
        }

        // verificar se existe uma loja com o mesmo ID, nome

        $store = Store::where('client_id', $userId)
            ->first();

        if(!$store) {
            return response()->json([
                'errorMessage' => 'Loja não encontrada para o usuário informado!',
            ], 404);
        }

        return response()->json([
            'successMessage' => 'Loja encontrada!',
            'store' => $store,
        ]);
    }
}

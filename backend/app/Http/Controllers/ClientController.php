<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;

class ClientController extends Controller
{
    public function registerClient(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'clientName' => 'sometimes|string',
            'clientEmail' => 'required|string|email',
            'clientPassword' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        //verificar se existe um client com mesmo id, email

        $newClientData = New Client([
            'name' => $data['clientName'],
            'email' => $data['clientEmail'],
            'password' => $data['clientPassword'],
        ]);

        $newClientData->save();

        return response()->json([
            'successMessage' => 'Usuário criado com sucesso!',
        ], 200);

    }

    public function getClients() {
        $clients = Client::all();

        return response()->json([
            'successMessage' => 'Usuários encontrados!',
            'clients' => $clients,
        ], 200);
    }
}

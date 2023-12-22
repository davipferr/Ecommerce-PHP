<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
use App\Models\ClientAccessToken;

class ClientController extends Controller
{
    public function registerClient(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'client_name' => 'required|string',
            'client_email' => 'required|string|email',
            'client_password' => 'required|string',
            'access_token' => 'required|string',
            'access_token_expiration_time' => 'required|date',
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errorMessage' => $validator->errors(),
            ], 400);
        }

        //verificar se existe um client com mesmo id, email

        $newClientData = New Client([
            'name' => $data['client_name'],
            'email' => $data['client_email'],
            'password' => $data['client_password'],
        ]);

        $newClientData->save();

        $newClientAccessToken = New ClientAccessToken([
            'client_id' => $newClientData->id,
            'access_token' => $data['access_token'],
            'access_token_expiration_time' => $data['access_token_expiration_time'],
            'refresh_token' => $data['refresh_token'],
        ]);

        $newClientAccessToken->save();


        return response()->json([
            'successMessage' => 'Usuário criado com sucesso!',
        ], 200);

    }


    public function getClientByFirebase($email, $access_token) {
        $client = Client::where('email', $email)->select('id', 'name', 'email', 'email_verified')->first();

        if (!$client) {

            return response()->json([
                'errorMessage' => 'Usuário não encontrado!',
            ], 404);

        } else {

            $token = $client->clientAccessTokens()->where('access_token', $access_token)->first();

            if (!$token) {
                return response()->json([
                    'errorMessage' => 'Token não encontrado!',
                    'client' => $client,
                ], 404);
            }

            //validar se token é válido
        }

        return response()->json([
            'successMessage' => 'Usuário e Token encontrados!',
            'client' => $client,
            'client_tokens' => $token,
        ], 200);
    }

    public function addRefreshTokenExpiration(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'client_id' => 'required|integer',
            'refresh_token_expiration_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errorMessage' => $validator->errors(),
            ], 400);
        }

        $hasExpirationTime = ClientAccessToken::where('client_id', $data['client_id'])
                                                ->select('refresh_token_expiration_time')
                                                ->first();

        if ($hasExpirationTime) {
            return response()->json([
                'sucessMessage' => 'Refresh token possui um expiration time!',
                'refresh_token_expiration_time' => $hasExpirationTime,
            ], 200);
        }

        $addToken = ClientAccessToken::where('client_id', $data['client_id'])->update([
            'refresh_token_expiration_time' => $data['refresh_token_expiration_time'],
        ]);

        if (!$addToken) {
            return response()->json([
                'errorMessage' => 'Erro ao atualizar token!',
            ], 400);
        }

        return response()->json([
            'successMessage' => 'Token atualizado com sucesso!',
            'refresh_token_expiration_time' => $data['refresh_token_expiration_time'],
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

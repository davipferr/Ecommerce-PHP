<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'access_token',
        'access_token_expiration_time',
        'refresh_token',
        'refresh_token_expiration_time',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public static function validateRefreshToken($client_id) {

        $refreshToken = ClientAccessToken::where('client_id', $client_id)
                                        ->select('refresh_token_expiration_time')
                                        ->first();

        if (!$refreshToken->refresh_token_expiration_time || $refreshToken->refresh_token_expiration_time < date('Y-m-d H:i:s', strtotime('-3 hour'))) {

            $access_token = ClientAccessToken::where('client_id', $client_id)
                                        ->select('access_token')
                                        ->first();

            $deleteClientTokens = ClientAccessToken::where('client_id', $client_id)
                                                    ->first()
                                                    ->delete();

            if (!$deleteClientTokens) {
                return response()->json([
                    'errorMessage' => 'Não foi posível excluir o token de acesso',
                ], 500);
            }

            $new_token = ClientAccessToken::createAccessToken($client_id, $access_token->access_token);

            return [
                'successMessage' => 'Token de acesso criado!',
                'newAccessToken' => $new_token['newAccessToken'],
                'newExpirationTime'=> $new_token['newExpirationTime'],
            ];
        }

        return true;
    }

    public static function newExpirationTime($add_time = null, $time_type = null) {
        $newExpirationTime = date('Y-m-d H:i:s', strtotime("-3 hour, {$add_time} {$time_type}"));

        return $newExpirationTime;
    }

    public static function addNewExpirationTime($client_id, $new_token_expiration) {
        $addNewExpirationTime = ClientAccessToken::where('client_id', $client_id)->update([
            'access_token_expiration_time' => $new_token_expiration,
        ]);

        if (!$addNewExpirationTime) {
            return response()->json([
                'errorMessage' => 'Não foi possível atualizar o token de acesso'
            ], 500);
        }

        return true;
    }

    public static function createAccessToken($client_id, $new_access_token) {

        $new_expiration_time = ClientAccessToken::newExpirationTime(7, "day");

        $add_token = new ClientAccessToken([
            'client_id' => $client_id,
            'access_token' => $new_access_token,
            'access_token_expiration_time' => $new_expiration_time,
        ]);

        $add_token->save();

        if (!$add_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível salvar o novo token de acesso.
                Tente novamente mais tarde',
            ], 500);
        }

        return [
            'newAccessToken' => $new_access_token,
            'newExpirationTime'=> $new_expiration_time,
        ];
    }

    public static function createRefreshToken($client_id, $new_refresh_token) {
        $new_expiration_time = ClientAccessToken::newExpirationTime(30, "day");

        $add_refresh_token = ClientAccessToken::where('client_id', $client_id)
                                        ->first()
                                        ->update([
                                            'refresh_token' => $new_refresh_token,
                                            'refresh_token_expiration_time' => $new_expiration_time,
                                        ]);

        if (!$add_refresh_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível salvar o token de lembrança.
                Tente novamente mais tarde',
            ], 500);
        }

        return [
            'newRefreshToken' => $new_refresh_token,
            'newExpirationTime'=> $new_expiration_time,
        ];
    }
}

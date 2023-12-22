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
}

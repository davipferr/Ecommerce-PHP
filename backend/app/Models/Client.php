<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'email_verified',
        'password',
    ];

    public function clientAccessTokens()
    {
        return $this->hasOne(ClientAccessToken::class);
    }
}

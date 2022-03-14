<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory;

    protected $table = 'investimentos';
    protected $fillable = [
        'user_id',
        'nome_investidor',
        'data_investimento',
        'valor_investimento'
    ];
}

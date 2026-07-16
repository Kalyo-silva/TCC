<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mantenedor extends Model
{
    protected $table = "mantenedores";

    protected $fillable = ['nome', 'cidade', 'uf', 'bairro', 'logradouro', 'cep'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\instituicao;

class mantenedor extends Model
{
    protected $table = "mantenedores";

    protected $fillable = ['nome', 'cidade', 'uf', 'bairro', 'logradouro', 'cep'];

    public function instituicoes(): HasMany{
        return $this->hasMany(instituicao::class);
    }
}

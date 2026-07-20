<?php

namespace App\Models;
use App\Models\mantenedor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class instituicao extends Model
{
    protected $table = 'instituicoes';
    protected $fillable = ['mantenedor_id', 'nome', 'cidade', 'uf', 'bairro', 'logradouro', 'cep', 'sigla', 'logo'];

    public function mantenedor() : belongsTo{
        return $this->belongsTo(mantenedor::class);
    }
}

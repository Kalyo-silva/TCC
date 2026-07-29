<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class criterio extends Model
{
    protected $table = "criterios";

    protected $fillable = ['indicador_id', 'descricao', 'sequencia'];

    public function indicador(): belongsTo{
        return $this->belongsTo(indicador::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class indicador extends Model
{
    protected $table = 'indicadores';

    protected $fillable = ['dimensao_id', 'descricao', 'sequencia'];

    public function dimensao() : belongsTo{
        return $this->belongsTo(dimensao::class);
    }
    public function criterios() : hasMany{
        return $this->hasMany(criterio::class);
    }
}

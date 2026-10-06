<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class avaliacao_indicador extends Model
{
    protected $table = 'avaliacao_indicador';

    protected $fillable = ['avaliacao_id', 'indicador_id', 'nota', 'observacao'];

    public function avaliacao(): belongsTo{
        return $this->belongsTo(avaliacao::class);
    }

    public function indicador(): belongsTo{
        return $this->belongsTo(indicador::class, 'indicador_id', 'id');
    }

    public function evidencias(): HasMany{
        return $this->hasMany(avaliacao_evidencia::class, 'avaliacao_indicador_id', 'id');
    }
}

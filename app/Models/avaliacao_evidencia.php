<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class avaliacao_evidencia extends Model
{
    protected $table = 'avaliacao_evidencia';
    protected $fillable = ['id_avaliacao', 'id_instrumento', 'id_dimensao', 'id_indicador', 'id_evidencia'];
    
    public function avaliacao(){
        return $this->belongsTo(avaliacao::class);
    }
    public function instrumento(){
        return $this->belongsTo(avaliacao::class);
    }    
    public function dimensao(){
        return $this->belongsTo(dimensao::class);
    }
    public function indicador(){
        return $this->belongsTo(indicador::class);
    }
}

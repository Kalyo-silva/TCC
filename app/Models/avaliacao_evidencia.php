<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class avaliacao_evidencia extends Model
{
    protected $table = 'avaliacao_evidencia';
    protected $fillable = ['avaliacao_indicador_id', 'evidencia_id'];
    
    public function avaliacao_indicador(){
        return $this->belongsTo(avaliacao_indicador::class);
    }
    public function evidencia(){
        return $this->belongsTo(evidencia::class);
    }    
}

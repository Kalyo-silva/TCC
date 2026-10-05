<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class avaliacao_corpodocente extends Model
{
    protected $table = 'avaliacao_corpodocente';
    protected $fillable = ['avaliacao_id', 'curso_id', 'professor_id', 'coordenador'];
    
    public function avaliacao(){
        return $this->belongsTo(avaliacao::class);
    }
    public function curso(){
        return $this->belongsTo(curso::class);
    }    
    public function professor(){
        return $this->hasMany(professor::class, 'id', 'professor_id');
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class evidencia extends Model
{
    protected $table = 'evidencias';

    protected $fillable = ['titulo', 'ano', 'tipo', 'file_name', 'file_path', 'link', 'text'];

    public function avaliacoes() : hasMany{
        return $this->hasMany(avaliacao_evidencia::class,'evidencia_id', 'id');
    }
}

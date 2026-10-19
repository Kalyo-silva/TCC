<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class professor extends Model
{
    protected $table = 'professores';
    protected $fillable = ['nome', 'data_admissao', 'titulacao', 'regime', 'vinculo',' lattes'];

    
    public function cursos(): hasMany{
        return $this->hasMany(curso_professores::class, 'professor_id', 'id');
    }

    public function avaliacoes(): HasMany{
        return $this->hasMany(avaliacao_corpodocente::class, 'professor_id', 'id');
    }
}

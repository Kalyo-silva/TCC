<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\professor;
use App\Models\instituicao;

class curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nome', 'instituicao_id', 'coordenador_id'];

    public function coordenador(): HasOne{
        return $this->HasOne(professor::class, 'id', 'coordenador_id');
    }

    public function professores(): hasMany{
        return $this->hasMany(curso_professores::class, 'curso_id', 'id');
    }

    public function instituicao() : BelongsTo{
        return $this->BelongsTo(instituicao::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\curso_professores;
use App\Models\instituicao;

class curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nome', 'instituicao_id'];

    public function professores(): HasMany{
        return $this->HasMany(curso_professores::class, 'curso_id', 'id');
    }

    public function instituicao() : BelongsTo{
        return $this->BelongsTo(instituicao::class);
    }
}

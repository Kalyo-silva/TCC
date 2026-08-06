<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Models\professor;
use App\Models\instituicao;

class curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nome', 'instituicao_id', 'professsor_id'];

    public function professor() : belongsTo{
        return $this->belongsTo(professor::class);
    }
    public function instituicao() : BelongsTo{
        return $this->BelongsTo(instituicao::class);
    }
}

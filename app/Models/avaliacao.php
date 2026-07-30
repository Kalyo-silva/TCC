<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = ['curso_id', 'instrumento_id', 'ano', 'descricao', 'data_inicio', 'data_fim', 'usuario_id'];

    public function instrumento(): belongsTo{
        return $this->belongsTo(instrumento::class);
    }

    public function curso(): belongsTo{
        return $this->belongsTo(curso::class);
    }

    public function usuario(): belongsTo{
        return $this->belongsTo(User::class);
    }
}
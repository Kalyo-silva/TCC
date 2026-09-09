<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\professor;
use App\Models\curso;

class curso_professores extends Model
{
    protected $table = 'curso_professores';

    protected $fillable = ['curso_id', 'professor_id'];

    public function professor(): hasOne{
        return $this->hasOne(professor::class, 'id', 'professor_id');
    }

    public function curso(): BelongsTo{
        return $this->BelongsTo(curso::class, 'id', 'curso_id');
    }
}

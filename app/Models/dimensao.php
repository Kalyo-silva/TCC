<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class dimensao extends Model
{
    protected $table = 'dimensoes';

    protected $fillable = ['instrumento_id', 'descricao', 'sequencia'];

    public function instrumento() : belongsTo{
        return $this->belongsTo(instrumento::class);
    }
}

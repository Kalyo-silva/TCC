<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
class dimensao extends Model
{
    protected $table = 'dimensoes';

    protected $fillable = ['instrumento_id', 'descricao', 'sequencia'];

    public function instrumento() : belongsTo{
        return $this->belongsTo(instrumento::class);
    }
    public function indicadores(): hasMany{
        return $this->hasMany(indicador::class);
    }
}

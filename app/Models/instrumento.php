<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class instrumento extends Model
{
    protected $table = 'instrumentos';

    protected $fillable = ['titulo', 'ano'];

    public function dimensoes(): hasMany{
        return $this->hasMany(dimensao::class);
    }
}

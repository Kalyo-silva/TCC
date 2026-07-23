<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class instrumento extends Model
{
    protected $table = 'instrumentos';

    protected $fillable = ['titulo', 'ano'];
}

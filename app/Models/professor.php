<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class professor extends Model
{
    protected $table = 'professores';
    protected $fillable = ['nome', 'data_admissao', 'titulacao', 'regime', 'vinculo',' lattes'];
}

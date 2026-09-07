<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class evidencia extends Model
{
    protected $table = 'evidencias';

    protected $fillable = ['titulo', 'ano', 'tipo', 'file_path', 'link', 'text'];
}

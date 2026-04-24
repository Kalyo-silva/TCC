<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MantenedorController extends Controller
{
    public function index(){
        return view('mantenedor.index');
    }
}

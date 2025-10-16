<?php

namespace App\Http\Controllers;

use App\Models\User; // Para acessar os utilizadores
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Método que lista todos os utilizadores
    public function index()
    {
        // Busca todos os utilizadores na tabela 'users'
        $users = User::all();

        // Retorna a view e passa os dados
        return view('users.index', compact('users'));
    }
}

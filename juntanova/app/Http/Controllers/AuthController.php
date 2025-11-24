<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('site.login');
    }

    public function showRegister()
    {
        return view('site.cadastro');
    }

    public function login(Request $request)
    {
        return $this->attemptLogin($request, false);
    }

    public function loginAdmin(Request $request)
    {
        return $this->attemptLogin($request, true);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['required', 'string', 'max:14'],
            'cep' => ['nullable', 'string', 'max:10'],
            'rua' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:2'],
        ]);

        $usuario = Usuario::create([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => Hash::make($data['password']),
            'telefone' => $data['telefone'] ?? '',
            'cpf' => $data['cpf'],
            'cep' => $data['cep'] ?? null,
            'rua' => $data['rua'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
            'pais' => 'Brasil',
            'is_admin' => false,
        ]);

        Auth::login($usuario, false);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'Cadastro realizado com sucesso.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function attemptLogin(Request $request, bool $apenasAdmin)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], false)) {
            $request->session()->regenerate();

            $usuario = Auth::user();
            if ($apenasAdmin && ! $usuario?->is_admin) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'Apenas administradores podem acessar o painel.'])
                    ->withInput($request->except('password'));
            }

            return redirect()->intended($apenasAdmin ? route('admin.dashboard') : route('home'));
        }

        return back()
            ->withErrors(['email' => 'Credenciais inválidas.'])
            ->withInput($request->except('password'));
    }
}

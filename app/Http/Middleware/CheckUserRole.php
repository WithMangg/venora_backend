<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleMenu;
use App\Models\Menu;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }


        // Ambil role_id pengguna yang login
        $roleId = Auth::user()->role_id;


        // Ambil menu_id yang diizinkan untuk role ini
        $roleMenu = RoleMenu::where('role_id', $roleId)->first();
        $allowedMenuIds = json_decode($roleMenu->menu_id, true);

        // Ambil menu yang cocok dengan URL saat ini
        $currentRouteName = $request->route()->getName();
        // dd($currentRouteName);
        $menu = Menu::where('route', $currentRouteName)->first();

        // Validasi apakah pengguna memiliki akses ke menu ini
        if ($menu && !in_array($menu->id, $allowedMenuIds)) {
            // Redirect jika tidak diizinkan
            return redirect()->route('unauthorized'); // Buat halaman unauthorized
        }

        return $next($request);
    }
}

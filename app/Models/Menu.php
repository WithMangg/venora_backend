<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'icon',
        'route',
        'parent_id',
        'menu_order'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('menu_order');
    }
}

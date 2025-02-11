<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class form_nobutton extends Component
{
    public $form;
    public $title;

    public function __construct($form = null, $title = null)
    {
        $this->form = $form;
        $this->title = $title;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form_nobutton');
    }
}


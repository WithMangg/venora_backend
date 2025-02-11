<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    public $form;
    public $title;

    public function __construct($form, $title)
    {
        $this->form = $form;
        $this->title = $title;

    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form');
    }
}

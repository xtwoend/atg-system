<?php

namespace App\View\Components;

use Closure;
use App\Models\Atg;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SidebarMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $atgs = Atg::orderBy('name')->get();
        return view('components.sidebar-menu', compact('atgs'));
    }
}

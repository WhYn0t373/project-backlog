<?php

namespace App\View\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Navbar extends Component
{
    public function __construct()
    {
        Log::info('Navbar component constructed.');
        parent::__construct();
    }

    public function render()
    {
        Log::debug('Rendering Navbar component.');
        return view('livewire.navbar');
    }
}
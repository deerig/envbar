<?php

namespace DeeRig\EnvBar;

use Illuminate\Contracts\View\View;

class Renderer
{
    public function renderHead(): string
    {
        return '<link href="' . mix('/css/app.css', 'vendor/envbar') . '" rel="stylesheet" />';
    }

    public function renderBar(): View
    {
        return view('envbar::components.bar');
    }
}

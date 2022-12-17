<?php

namespace DeeRig\EnvAlert;

use Illuminate\Contracts\View\View;

class Renderer
{
    public function renderHead(): string
    {
        return '<link href="' . mix('/css/app.css', 'vendor/envalert') . '" rel="stylesheet" />';
    }

    public function renderBar(): View
    {
        return view('envalert::components.bar');
    }
}

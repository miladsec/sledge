<?php


namespace MiladZamir\Sledge\Builder;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Script
{
    public Application|string|View|\Illuminate\Foundation\Application|Factory $scriptFile = '';

    public function __construct($scriptFile)
    {
        $this->scriptFile =  view("sledge::scripts." . $scriptFile);
    }

}

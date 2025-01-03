<?php


namespace MiladZamir\Sledge\Builder;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Script
{
    public $scriptFile = '';

    public function __construct($scriptFile)
    {
        $this->scriptFile =  view("sledge::scripts." . $scriptFile);
    }

}

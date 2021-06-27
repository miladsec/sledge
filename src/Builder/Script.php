<?php


namespace MiladZamir\Sledge\Builder;


class Script
{
    public $scriptFile = '';

    public function __construct($scriptFile)
    {
        $this->scriptFile =  view("sledge::scripts." . $scriptFile);
    }

}

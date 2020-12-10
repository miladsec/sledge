<?php


namespace MiladZamir\Sledge;


class Container
{
    public function __construct()
    {

    }

    public function listenScript()
    {
        ob_start();
    }

    public function renderScript()
    {
        return ob_get_clean();
    }
}

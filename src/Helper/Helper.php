<?php

namespace MiladZamir\Sledge\Helper;

class UrlFinder
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function storeRoute()
    {
        $section = self::urlToArray('/', '-2');
        return route($section . '.store');
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function formRoute($config)
    {
        if ($config == 'create')
            return $this->storeRoute();
    }

    public static function urlToArray($splitter, $last = null)
    {
        $url = explode($splitter, url()->current());

        if ($last != null)
            $url = array_slice($url, $last)[0];

        return $url;
    }
}

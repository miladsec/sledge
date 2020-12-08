<?php


namespace MiladZamir\Sledge\Builder;


class NavLinkBuilder
{
    private $navLink;

    public function __construct($navLink)
    {
        $this->navLink = $navLink;
    }

    public function create()
    {
        $data = $this->navLink;
        return view('sledge::structure.navLink')->with(compact('data'));
    }

}

<?php


namespace MiladZamir\Sledge\Builder;


class Column
{
    public $name;
    public $title;
    public $callBack;
    public $variables;
    public $cssClass;
    public $icon;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function title($title): Column
    {
        $this->title = $title;
        return $this;
    }

    public function callBack($callBack): Column
    {
        $this->callBack = $callBack;
        return $this;
    }

    public function action($variables = [])
    {
        $this->variables = $variables;
        return $this;
    }

    public function cssClass($cssClass)
    {
        $this->cssClass = $cssClass;
        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

}

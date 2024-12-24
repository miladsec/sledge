<?php


namespace MiladZamir\Sledge\Builder;


use Closure;

class Column
{
    public string $name;
    public string $title;
    public Closure $callBack;
    public string $css;
    public string $icon;
    public bool $isAction;

    public $variable;
    public $route;
    public $key;

    public function __construct()
    {
        $this->isAction = false;
    }

    public function name($name): Column
    {
        $this->name = $name;
        return $this;
    }
    public function isAction($isAction): Column
    {
        $this->isAction = $isAction;
        return $this;
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

    public function action($variables = []): Column
    {
        $this->variables = $variables;
        return $this;
    }

    public function css($css): Column
    {
        $this->css = $css;
        return $this;
    }

    public function icon($icon): Column
    {
        $this->icon = $icon;
        return $this;
    }

    public function variable($variable): Column
    {
        $this->variable = $variable;
        return $this;
    }

    public function route($route): Column
    {
        $this->route = $route;
        return $this;
    }

    public function key($key): Column
    {
        $this->key = $key;
        return $this;
    }

}

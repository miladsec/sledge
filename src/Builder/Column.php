<?php

namespace MiladZamir\Sledge\Builder;

use Closure;

class Column
{
    public string $name;
    public string $title;
    public Closure $callBack;
    public array $cssClass;
    public string $icon;
    public string $queryString = '';
    public bool $isAction;
    public bool $isExtra;

    public $variables = [];
    public $route;
    public $key;
    public bool $accessControl;

    public $id;
    public $uiComponent;
    public $uiComponentData;
    /**
     * @var mixed
     */
    public $view;
    /**
     * @var mixed
     */
    public $alertCustomDetailMessage;

    public function __construct()
    {
        $this->isAction = false;
        $this->isExtra = false;
    }

    public function name($name): Column
    {
        $this->name = $name;
        return $this;
    }
    public function view($view): Column
    {
        $this->view = $view;
        return $this;
    }
    public function isAction($isAction): Column
    {
        $this->isAction = $isAction;
        return $this;
    }
    public function isExtra($isExtra): Column
    {
        $this->isExtra = $isExtra;
        return $this;
    }
    public function title($title): Column
    {
        $this->title = $title;
        return $this;
    }

    public function callBack(callable $callBack): Column
    {
        if (!$callBack instanceof Closure) {
            $callBack = Closure::fromCallable($callBack);
        }

        $this->callBack = $callBack;
        return $this;
    }

    public function action($variables = []): Column
    {
        $this->variables = $variables;
        return $this;
    }

    public function cssClass($css): Column
    {
        $this->cssClass = $css;
        return $this;
    }

    public function icon($icon): Column
    {
        $this->icon = $icon;
        return $this;
    }

    public function queryString($queryString): Column
    {
        $this->queryString = $queryString;
        return $this;
    }

    public function uiComponent($uiComponent, $data): Column
    {
        $this->uiComponent = $uiComponent;
        $this->uiComponentData =  $data;
        return $this;
    }

    public function id($id): Column
    {
        $this->id = $id;
        return $this;
    }

    public function variables(array $variables): Column
    {
        $this->variables = $variables;
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

    public function alertCustomDetailMessage($alertCustomDetailMessage): Column
    {
        $this->alertCustomDetailMessage = $alertCustomDetailMessage;
        return $this;
    }

    public function accessControl(bool $true)
    {
        $this->accessControl = $true;
        return $this;
    }
}


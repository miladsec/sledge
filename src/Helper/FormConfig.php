<?php

namespace MiladZamir\Sledge\Helper;

use Illuminate\Http\Request;

class FormConfig
{
    private $model;
    private $create;
    private $edit;
    public function __construct($model)
    {
        $model = explode("\\", $model);
        $this->model = lcfirst(end($model));

        $this->create = Helper::getActionStatus(url()->current(), 'create');
        $this->edit = Helper::getActionStatus(url()->current(), 'edit');
    }

    public function method()
    {
        if ($this->create)
            return 'POST';
        elseif ($this->edit)
            return 'POST';
        else
            return 'GET';
    }

    public function methodField()
    {
        if ($this->create)
            return '';
        elseif ($this->edit)
            return method_field('PATCH');
        else
            return '';
    }
    public function action()
    {
        if ($this->create)
            return route($this->model.'.store');
        elseif ($this->edit){
            $url = url()->current();
            $url = explode('/', $url);
            $id = array_slice($url, -2)[0];
            return route($this->model.'.update', [$this->model => $id]);
        }
        else
            return '';
    }



}

<?php


namespace MiladZamir\Sledge\Builder;


use MiladZamir\Sledge\Helper\Helper;

class Form
{
    public $headerData;
    public $bodyData;
    public $footerData;
    public $config;
    public $name;
    public $id;
    public $cssClass;
    public $novalidate;
    public $enctype;
    public $acceptCharset;
    public $autocomplete;
    public $type;
    public $label;
    public $validate;
    public $value;
    public $placeholder;
    public $uniqueId;
    public $selectConfig;
    public $oldValue;
    public $col;


    public function __construct($config)
    {
        $this->config = $config;
        $this->col = config('sledge.create.defaultCol');
        $this->uniqueId = Helper::createUniqueString(5);
    }

    public function open(): Form
    {
        $this->headerData = view('sledge::element.openForm')->with('data', $this);
        return $this;
    }

    public function close(): Form
    {
        $this->footerData = view('sledge::element.closeForm');
        return $this;
    }
    public function input($type): Form
    {
        $this->type = $type;

        switch ($this->type){
            case 'text':
            case 'number':
            case 'tel':
            case 'email':
                $this->bodyData = view('sledge::element.input')->with('data', $this);
                break;
            case 'submit':
                $this->footerData = view('sledge::element.submit')->with('data', $this);
                break;
            case 'select':
                $this->bodyData = view('sledge::element.select')->with('data', $this);
                break;
            case 'multiselect':
                $this->bodyData = view('sledge::element.multiselect')->with('data', $this);
                break;
            default:
                dd('!');
        }

        return $this;
    }

    public function name($name): Form
    {
        $this->name = $name;
        return $this;
    }

    public function placeholder($placeholder): Form
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function validate(array $validate): Form
    {
        $this->validate = '';
        foreach ($validate as $value){
            foreach ($value as $key => $v){
                $this->validate .= ' '. $key .'="'. $v .'" ';
            }
        }
        return $this;
    }

    public function value($value): Form
    {
        $this->value = $value;
        return $this;
    }

    public function label($label): Form
    {
        $this->label = $label;
        return $this;
    }

    public function id($id): Form
    {
        $this->id = $id;
        return $this;
    }

    public function cssClass($cssClass): Form
    {
        $this->cssClass = $cssClass;
        return $this;
    }

    public function novalidate($novalidate): Form
    {
        $this->novalidate = $novalidate;
        return $this;
    }

    public function enctype($enctype): Form
    {
        $this->enctype = $enctype;
        return $this;
    }

    public function autocomplete($autocomplete = 'off'): Form
    {
        $this->autocomplete = $autocomplete;
        return $this;
    }

    public function acceptCharset($acceptCharset = 'utf-8'): Form
    {
        $this->acceptCharset = $acceptCharset;
        return $this;
    }

    public function selectConfig($selectConfig): Form
    {
        $this->selectConfig = $selectConfig;
        return $this;
    }

    public function oldValue($oldValue): Form
    {
        $this->oldValue = $oldValue;
        return $this;
    }

    public function col($col= 'col-6'): Form
    {
        $this->col = $col;
        return $this;
    }

}

<?php


namespace MiladZamir\Sledge\Builder;


use MiladZamir\Sledge\Helper\Helper;

class Field
{

    private $name;
    private $label;

    public function __construct($name, $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function text($value, $meta = null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'value' => $value,
            'name' => $this->name,
            'label' => $this->label,
            'class' => $meta['class'] ?? '',
            'id' => $meta['id'] ?? '',
            'placeholder' => $meta['placeholder'] ?? '',
        ];
        return view('sledge::element.text')->with('data', $data);
    }

    public function select($value, $action = null, $meta = null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $this->name,
            'value' => $value,
            'action' => $action,
            'label' => $this->label,
            'class' => $meta['class'] ?? '',
            'id' => $meta['id'] ?? '',
            'placeholder' => $meta['placeholder'] ?? '',
        ];
        return view('sledge::element.select')->with('data', $data);
    }

    public function multiSelect($value, $action = null, $meta = null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $this->name,
            'value' => $value,
            'action' => $action,
            'label' => $this->label,
            'class' => $meta['class'] ?? '',
            'id' => $meta['id'] ?? '',
            'placeholder' => $meta['placeholder'] ?? '',
        ];
        return view('sledge::element.multiSelect')->with('data', $data);
    }
    public function checkbox($value, $action = null, $meta = null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $this->name,
            'value' => $value,
            'action' => $action,
            'label' => $this->label,
            'class' => $meta['class'] ?? '',
            'id' => $meta['id'] ?? '',
            'placeholder' => $meta['placeholder'] ?? '',
        ];
        return view('sledge::element.checkbox')->with('data', $data);
    }


    public function submit($value, $name = null, $class = null, $id = null)
    {
        $data = [
            'value' => $value,
            'name' => $name,
            'class' => $class,
            'id' => $id
        ];

        return view('sledge::element.submit')->with('data', $data);
    }

}

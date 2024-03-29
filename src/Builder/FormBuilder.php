<?php
namespace MiladZamir\Sledge\Builder;

use MiladZamir\Sledge\Helper\Helper;
use MiladZamir\Sledge\Helper\FormConfig;

class FormBuilder
{
    private $data = [
        'header' => [],
        'body' => [],
        'footer' => []
    ];
    private $model;
    private $formAction;
    private $formMethod;
    private $formMethodField;
    private $navLink;

    public function __construct($model, $navLink = null, $form = 'auto')
    {
        $this->model = $model;

        $formConfig = new FormConfig($this->model);

        if ($form == 'auto'){
            $this->formMethod = $formConfig->method();
            $this->formMethodField = $formConfig->methodField();
            $this->formAction = $formConfig->action();
        }else{
            $this->formMethod = $form[0];//method
            $this->formMethodField = method_field($form[1]);//methodField
            $this->formAction = $form[2];//action
        }

        if ($navLink != null){
            $navLink = new NavLinkBuilder($navLink);
            $this->navLink = $navLink->create();
        }

    }

    public function openForm($name = null, $enctype = null, $novalidate = 'novalidate', $autocomplete = 'off', $accept_charset = 'utf-8', $class= null, $id= null)
    {
        $data = [
            'action' => $this->formAction,
            'method' => [$this->formMethod, $this->formMethodField],
            'name' => $name,
            'enctype' => $enctype,
            'novalidate' => $novalidate,
            'autocomplete' => $autocomplete,
            'accept-charset' => $accept_charset,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['header'], view('sledge::structure.openForm')->with('data', $data));
    }

    public function input($type, $name, $label, $validate=[], $value=null,$placeholder=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'validate' => $validate,//implode(" ", $validate)
            'label' => $label,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.input')->with('data', $data));
    }

    public function file($name, $label, $validate=[],$value = null, $placeholder=null, $size = [], $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'validate' => $validate,//implode(" ", $validate)
            'value' => $value,
            'label' => $label,
            'placeholder' => $placeholder,
            'size' => $size,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.file')->with('data', $data));
    }

    public function select($name, $label, $dKey, $validate=[], $value, $old=null, $placeholder=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'label' => $label,
            'dKey' => $dKey,
            'validate' => $validate,
            'value' => $value,
            'old' => $old,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
        ];

        array_push($this->data['body'], view('sledge::element.select')->with('data', $data));
    }

    public function multiSelect($name, $label, $dKey, $validate=[], $value, $old=null, $placeholder=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'label' => $label,
            'dKey' => $dKey,
            'validate' => $validate,
            'value' => $value,
            'old' => $old,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.multiSelect')->with('data', $data));
    }

    public function checkbox($name, $label, $dKey, $validate=[], $value, $old=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'label' => $label,
            'dKey' => $dKey,
            'validate' => $validate,
            'value' => $value,
            'old' => $old,
            'class' => $class,
            'id' => $id,
        ];

        array_push($this->data['body'], view('sledge::element.checkbox')->with('data', $data));
    }

    public function textarea($type, $name, $label, $validate=[], $value=null, $row = null,$placeholder=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'validate' => $validate,//implode(" ", $validate)
            'row' => $row,
            'label' => $label,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.textarea')->with('data', $data));
    }

    public function radio($name, $label, $dKey, $validate=[], $value, $old=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'label' => $label,
            'dKey' => $dKey,
            'validate' => $validate,
            'value' => $value,
            'old' => $old,
            'class' => $class,
            'id' => $id,
        ];

        array_push($this->data['body'], view('sledge::element.radios')->with('data', $data));
    }

    public function date($name, $label, array $bind,$validate=[], $value=null, $old=null, $placeholder=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'label' => $label,
            'bind' => $bind,
            'validate' => $validate,
            'value' => $value,
            'old' => $old,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.datePicker')->with('data', $data));
    }

    public function holder($selector, $label, array $bind, $value = null, $old=null, $class=null, $id=null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'selector' => $selector,
            'label' => $label,
            'bind' => $bind,
            'value' => $value,
            'old' => $old,
            'class' => $class,
            'id' => $id,
        ];

        array_push($this->data['body'], view('sledge::element.holder')->with('data', $data));
    }

    public function customView($src, $data = null, $col = 'col-6')
    {
        array_push($this->data['body'], view('sledge::'. $src)->with(compact('data', 'col')));
    }

    public function submit($value, $name = null, $class = null, $id = null)
    {
        $data = [
            'value' => $value,
            'name' => $name,
            'class' => $class,
            'id' => $id
        ];

        array_push($this->data['footer'], view('sledge::element.submit')->with('data', $data));
    }

    public function closeForm()
    {
        array_push($this->data['footer'], view('sledge::structure.closeForm'));
    }

    public function render()
    {
        return ['data' =>$this->data, 'navLink' => $this->navLink];
    }

}

<?php
namespace MiladZamir\Sledge\Builder;

use MiladZamir\Sledge\Helper\UrlFinder;

class FormBuilder extends Field
{
    private $config;
    private $inputClass;

    public function __construct(array $data)
    {
        $this->config = $data['config'];
        $this->inputClass = $data['inputClass'];
    }

    public function element($name, $label)
    {
        return new Field($name, $label);
    }

    public function openForm($name = null, $enctype = null, $novalidate = 'novalidate', $autocomplete = 'off', $accept_charset = 'utf-8', $class= null, $id= null)
    {
        $data = [
            'action' => $this->formAction(),
            'method' => $this->formMethod(),
            'name' => $name,
            'enctype' => $enctype,
            'novalidate' => $novalidate,
            'autocomplete' => $autocomplete,
            'accept-charset' => $accept_charset,
            'class' => $class,
            'id' => $id,
        ];

        return view('sledge::structure.openForm')->with('data', $data);
    }
    public function formAction()
    {
        $urlFinder = new UrlFinder();
        return $urlFinder->formRoute($this->config);
    }
    public function formMethod()
    {
        if ($this->config == 'create')
            return 'POST';
    }

    public function closeForm()
    {
        return view('sledge::structure.closeForm');
    }





  /*  public function text($value, $name, $class = null, $placeholder = null)
    {
        return '<input type="text" value="'. $value .'" name="'. $name .'" class="'. $this->inputClass .' '. $class . '" placeholder="'. $placeholder .'"/>';
    }*/
/*
    public function number($value, $name, $class = null, $placeholder = null)
    {
        return '<input type="number" value="'. $value .'" name="'. $name .'" class="'. $this->inputClass .' '. $class . '" placeholder="'. $placeholder .'"/>';
    }

    public function password($value, $name, $class = null, $placeholder = null)
    {
        return '<input type="password" value="'. $value .'" name="'. $name .'" class="'. $this->inputClass .' '. $class . '" placeholder="'. $placeholder .'"/>';
    }

    public function email($value, $name, $class = null, $placeholder = null)
    {
        return '<input type="email" value="'. $value .'" name="'. $name .'" class="'. $this->inputClass .' '. $class . '" placeholder="'. $placeholder .'"/>';
    }

    public function radio($value, $name, $class = null ,$id = null)
    {
        return '<input type="radio"
        value="'. $value .'"
        name="'. $name .'"
        class="'. $this->inputClass .' '. $class . '"
        id="'. $id .'"
        />';
    }

    public function checkbox($value, $name, $class = null ,$id = null)
    {
        return '<input type="checkbox"
        value="'. $value .'"
        name="'. $name .'"
        class="'. $this->inputClass .' '. $class . '"
        id="'. $id .'"
        />';
    }
    public function file($name, $class = null ,$id = null)
    {
        return '<input type="file"
        name="'. $name .'"
        class="'. $this->inputClass .' '. $class . '"
        id="'. $id .'"
        />';
    }

    public function label($value, $for)
    {
        return '<label for="'. $for .'">'. $value.'</label>';
    }

    public function submit($value, $name, $class = null)
    {
        return '<input type="submit" value="'. $value .'" name="'. $name .'" class="'. $class .'" />';
    }

    public function close()
    {
        return '</form>';
    }

    public function csrf()
    {
        return csrf_field();
    }


    public function render($formBody)
    {
        return [
            'header' => $formBody[0],
            'body' => array_slice($formBody, 1, -2),
            'footer' => array_slice($formBody, -2),
            ];
    }*/

}

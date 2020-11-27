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
}

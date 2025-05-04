<?php


namespace MiladZamir\Sledge\Builder;


use MiladZamir\Sledge\Helper\Helper;

class Form
{
    public $headerData;
    public $bodyData;
    public $sectionBodyData;
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
    public $inputConfig;
    public $oldValue;
    public $col;
    public $row;

    public $selectKeyValue;
    public $timePicker;
    public $ajaxEvent;
    public $qrCode;
    public array $inputButton;
    public $isChecked;
    public $groupTitle;
    public $isRenderOutOfDiv;
    public $isTagEnabled;
    /**
     * @var false|mixed
     */
    public $isDisabled;
    public $isReadOnly;

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

    public function section($path): Form
    {
        $this->sectionBodyData = view('sledge::custom_view.'.$path)->with('data', $this);
        return $this;
    }

    public function close(): Form
    {
        $this->footerData = view('sledge::element.closeForm');
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function input($type): Form
    {
        $this->type = $type;

        switch ($this->type){
            case 'text':
            case 'number':
            case 'tel':
            case 'email':
            case 'hidden':
            case 'file':
                $this->bodyData = view('sledge::element.input')->with('data', $this);
                break;
            case 'select':
                $this->bodyData = view('sledge::element.select')->with('data', $this);
                break;
            case 'multiSelect':
                $this->bodyData = view('sledge::element.multiselect')->with('data', $this);
                break;
            case 'radio':
                $this->bodyData = view('sledge::element.radios')->with('data', $this);
                break;
            case 'checkbox':
                $this->bodyData = view('sledge::element.checkbox')->with('data', $this);
                break;
            case 'textarea':
                $this->bodyData = view('sledge::element.textarea')->with('data', $this);
                break;
            case 'qrcode':
                $this->bodyData = view('sledge::element.qrcode')->with('data', $this);
                break;
            case 'submit':
                $this->footerData = view('sledge::element.submit')->with('data', $this);
                break;
            case 'datepicker':
                $this->bodyData = view('sledge::element.datePicker')->with('data', $this);
                break;
            case 'timePicker':
                $this->bodyData = view('sledge::element.timePicker')->with('data', $this);
                break;
            case 'switch':
                $this->bodyData = view('sledge::element.switch')->with('data', $this);
                break;
            case 'tagify':
                $this->bodyData = view('sledge::element.tagify')->with('data', $this);
                break;
            case 'group_title':
                $this->bodyData = view('sledge::element.group_title')->with('data', $this);
                break;
            default:
                try {
                    $this->bodyData = view("sledge::custom_input.{$type}")->with('data', $this);
                }catch (\Exception $exception){
                    throw new \Exception("Form input view: {$type} Not Found!");
                }
        }

        return $this;
    }

    public function name($name, $editOldValuePath=null): Form
    {
        $this->name = $name;

        if(isset($this->config->editData)){
            if($editOldValuePath != null){
                $this->oldValue = $this->config->editData->{$editOldValuePath};
            }else{
                $this->oldValue = $this->config->editData->{$this->name};
            }
        }

        return $this;
    }

    public function placeholder($placeholder): Form
    {
        $this->placeholder = $placeholder;
        return $this;
    }
    public function disabled($isDisabled=false): Form
    {
        $this->isDisabled = $isDisabled;
        return $this;
    }
    public function readOnly($isReadOnly=true): Form
    {
        $this->isReadOnly = $isReadOnly;
        return $this;
    }

    public function inputButton($title, $id, $cssClass): Form
    {
        $this->inputButton = [$title, $id, $cssClass];
        return $this;
    }

    public function ajaxEvent($onName, $routeName, $routeVariable, $responseValueKey, $oldIdSelected, $method='GET'): Form
    {
        $this->ajaxEvent = [$onName, $routeName, $routeVariable, $responseValueKey, $oldIdSelected, $method];
        return $this;
    }
    public function validate(array $validate): Form
    {
        $this->validate = '';
        foreach ($validate as $key=>$value){
            $this->validate .= ' '. $key .'='. $value .' ';
        }
        return $this;
    }

    public function value($value): Form
    {
        $this->value = $value;
        return $this;
    }
    public function isChecked($isChecked=true): Form
    {
        $this->isChecked = $isChecked;
        return $this;
    }

    public function groupTitle($title): Form
    {
        $this->groupTitle = $title;
        return $this;
    }
    public function isRenderOutOfDiv($isRenderOutOfDiv=true): Form
    {
        $this->isRenderOutOfDiv = $isRenderOutOfDiv;
        return $this;
    }

    public function qrCode($qrCode): Form
    {
        $this->qrCode = $qrCode;
        return $this;
    }

    public function label($label): Form
    {
        $this->label = $label;
        return $this;
    }
    public function isTagEnabled($isTagEnabled=true): Form
    {
        $this->isTagEnabled = $isTagEnabled;
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
        $this->novalidate = $novalidate ? 'novalidate': '';
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

    public function inputConfig($inputConfig): Form
    {
        $this->inputConfig = $inputConfig;
        return $this;
    }

    public function oldValue($oldValue): Form
    {
        $this->oldValue = $oldValue;
        return $this;
    }

    public function col($col = 'col-6'): Form
    {
        $this->col = $col;
        return $this;
    }

    public function row($row = '6'): Form
    {
        $this->row = $row;
        return $this;
    }

    public function selectKeyValue($keyValue): Form
    {
        $this->selectKeyValue = $keyValue;
        return $this;
    }

    public function timePicker($timePicker = false): Form
    {
        $this->timePicker = $timePicker;
        return $this;
    }



}

<?php

namespace MiladZamir\Sledge\Builder;

use http\Env\Request;
use MiladZamir\Sledge\Helper\FormConfig;
use MiladZamir\Sledge\Helper\Helper;

class Builder
{
    private $model;
    private $modelName;
    private $table = [];
    private $config;
    public $form;
    public $formData = [
        'header' => [],
        'body' => [],
        'footer' => []
    ];
    private $data;

    public function __construct($model)
    {
        $this->model = app($model);
        $this->modelName = $model;
    }

    public function column($name)
    {
        $this->table[] = new Column($name);
        return end($this->table);
    }

    public function config($config): Config
    {
        $this->config = new Config($config, $this->model, $this->modelName);
        return $this->config;
    }

    public function getDataTable($request): \Illuminate\Http\JsonResponse
    {
        $mmx = $this->config->value->count();
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');

        if ($request->search['value'] != null) {
            $data = $this->config->value;
            foreach ($this->config->searchAttributes as $key => $sv) {
                if ($key == 0) {
                    $data->where($sv, 'LIKE', "%" . ($request->search['value'] . "%"));
                } else {
                    $data->orWhere($sv, 'LIKE', "%" . ($request->search['value'] . "%"));
                }
            }
            $mmxF = $data->count();
            $data = $data->skip($start)->take($length)->get();
        } else {
            $data = $this->config->value->skip($start)->take($length)->get();
        }

        $page = ($start / $length) + 1;
        if (empty($page))
            $page = 1;

        if ($page < 0)
            $page = 1;

        $request->request->add(['page' => $page]);


        $lastD[][] = null;
        foreach ($data as $k => $dat) {
            $secData = clone $dat;
            foreach ($this->table as $key => $table) {
                if (is_array($table->variables) && !empty($table->variables)) {
                    $routeStrings = '';
                    $routeVariables = [];
                    foreach ($table->variables as $variable) {
                        foreach ($variable as $var => $dValue) {
                            $routeVariables += [$var => $dat->{$dValue}];
                        }
                    }
                    $route = route($table->name, $routeVariables);
                    $routeString = config('sledge.columnAction.route');
                    $routeString = str_replace('*1', $table->cssClass, $routeString);
                    $routeString = str_replace('*2', $route, $routeString);
                    $routeString = str_replace('*3', $table->icon, $routeString);
                    $routeString = str_replace('*4', $table->title, $routeString);
                    $routeStrings .= $routeString;

                    $lastD[$k][$key] = str_replace('*1', $routeStrings, config('sledge.columnAction.static'));
                    continue;
                }

                $str = explode('.', $table->name);
                $count = count($str);
                if ($table->name == '#') {
                    $lastD[$k][$key] = $k + 1;
                    continue;
                }
                if ($count == 1) {
                    if (!empty($table->callBack)) {
                        $lastD[$k][$key] = $table->callBack($dat->{$str[0]})->callBack;
                        continue;
                    }
                    $lastD[$k][$key] = $dat->{$str[0]};
                    continue;
                }
                if ($count > 1) {
                    for ($i = 0; $i < count($str); $i++) {
                        $dat = $dat->{$str[$i]};
                        if ($dat == null) {
                            $lastD[$k][$key] = '-';
                            break;
                        }
                        $lastD[$k][$key] = $dat;
                    }
                    if (!empty($table->callBack)) {
                        $lastD[$k][$key] = $table->callBack($dat)->callBack;
                    }
                }

                $dat = $secData;
            }
        }
        $this->data = $lastD;

        if ($this->data[0][0] == null)
            $this->data = [];

        return response()->json([
            'data' => $this->data,
            "draw" => $request->input('draw'),
            "recordsTotal" => $mmx,
            "recordsFiltered" => $mmxF ?? $mmx,
        ]);
    }

    public function render(): array
    {
        if (!empty($this->form)){
            foreach ($this->form as $form){
                if (!empty($form->headerData)){
                    array_push($this->formData['header'], $form->headerData);
                }elseif (!empty(!empty($form->bodyData))){
                    array_push($this->formData['body'], $form->bodyData);
                }elseif (!empty($form->footerData)){
                    array_push($this->formData['footer'], $form->footerData);
                }
            }
        }
        return ['table' => $this->table, 'button' => $this->config->button, 'navbar' => $this->config->navbar, 'form' => $this->formData];
    }

    public function form()
    {
        $this->form[] = new Form($this->config);
        return end($this->form);
    }

    public function file($name, $label, $validate = [], $value = null, $placeholder = null, $size = [], $class = null, $id = null)
    {
        $data = [
            'uniqueId' => Helper::createUniqueString(5),
            'name' => $name,
            'validate' => $validate,
            'value' => $value,
            'label' => $label,
            'placeholder' => $placeholder,
            'size' => $size,
            'class' => $class,
            'id' => $id,
        ];
        array_push($this->data['body'], view('sledge::element.file')->with('data', $data));
    }

    public function checkbox($name, $label, $dKey, $validate = [], $value, $old = null, $class = null, $id = null)
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

    public function textarea($type, $name, $label, $validate = [], $value = null, $row = null, $placeholder = null, $class = null, $id = null)
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

    public function radio($name, $label, $dKey, $validate = [], $value, $old = null, $class = null, $id = null)
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

    public function date($name, $label, array $bind, $validate = [], $value = null, $old = null, $placeholder = null, $class = null, $id = null)
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

    public function holder($selector, $label, array $bind, $value = null, $old = null, $class = null, $id = null)
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
        array_push($this->data['body'], view('sledge::' . $src)->with(compact('data', 'col')));
    }

    public function setScript($bladeFile, $data = null)
    {
        $this->script = view('sledge::scripts.' . $bladeFile)->with(compact('data'));
    }

    public function listenScript()
    {
        ob_start();
    }

    public function renderScript()
    {
        return ob_get_clean();
    }

}

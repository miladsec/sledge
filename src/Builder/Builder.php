<?php

namespace MiladZamir\Sledge\Builder;

use MiladZamir\Sledge\Helper\FormConfig;
use MiladZamir\Sledge\Helper\Helper;

class Builder
{
    private $model;
    private $modelName;
    private $route;
    private $value;
    private $table = [];
    private $columnAction = [];
    private $searchAttributes = [];
    private $button = [];
    private $navbar;
    private $module;
    private $formAction;
    private $formMethod;
    private $formMethodField;
    public $data = [
        'header' => [],
        'body' => [],
        'footer' => []
    ];

    public function __construct($model, $route)
    {
        $this->model = app($model);
        $this->modelName = $model;
        $this->route = $route;
    }

    public function queryConfig($orderBy = "id DESC", $where = null, $whereIn = null)
    {
        $this->value = $this->model;

        if ($orderBy != null)
            $this->value = $this->model->orderByRaw($orderBy);

        if ($where != null)
            $this->value = $this->model->where($where);

        if ($whereIn != null)
            $this->value = $this->model->whereIn($whereIn[0], $whereIn[1]);
    }

    public function dataTableConfig($searchAttributes = [])
    {
        $this->searchAttributes = $searchAttributes;
    }

    public function pageConfig($module, $button = 'auto', $navbar = 'auto')
    {
        $this->module = $module;

        $this->createButton($button);
        $this->createNavbar($navbar);
    }

    public function column($name, $text, $callBack = null)
    {
        $data = [
            'name' => $name,
            'text' => $text,
            'callBack' => $callBack
        ];
        array_push($this->table, $data);
    }

    public function columnAction($routeName, $variables = [], $title, $icon, $acl = false, $class = null)
    {
        $data = [
            'routeName' => $routeName,
            'variables' => $variables,
            'title' => $title,
            'icon' => $icon,
            'class' => ($class != null) ? implode(' ', $class) : '',
        ];
        array_push($this->columnAction, $data);
    }

    public function getDataTable($request)
    {
        $mmx = $this->value->count();

        $start = (int)$request->input('start');

        $length = (int)$request->input('length');

        if ($request->search['value'] != null) {
            $data = $this->value;
            foreach ($this->searchAttributes as $key => $sv) {
                if ($key == 0) {
                    $data->where($sv, 'LIKE', "%" . $request->search['value'] . "%");
                } else {
                    $data->orWhere($sv, 'LIKE', "%" . $request->search['value'] . "%");
                }
            }
            $mmxF = $data->count();
            $data = $data->skip($start)->take($length)->get();
        } else {
            $data = $this->value->skip($start)->take($length)->get();
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
                if (isset($table['columnAction'])) {
                    $routeStrings = '';
                    foreach ($table['columnAction'] as $ca) {
                        $routeVariables = [];
                        foreach ($ca['variables'] as $variable) {
                            foreach ($variable as $var => $v) {
                                $routeVariables += [$var => $dat->{$v}];
                            }
                        }
                        $route = route($ca['routeName'], $routeVariables);
                        $routeString = config('sledge.columnAction.route');
                        $routeString = str_replace('*1', $ca['class'], $routeString);
                        $routeString = str_replace('*2', $route, $routeString);
                        $routeString = str_replace('*3', $ca['icon'], $routeString);
                        $routeString = str_replace('*4', $ca['title'], $routeString);
                        $routeStrings .= $routeString;
                    }
                    $lastD[$k][$key] = str_replace('*1', $routeStrings, config('sledge.columnAction.static'));
                    continue;
                }

                $str = explode('.', $table['name']);
                $count = count($str);
                if ($table['name'] == '#') {
                    $lastD[$k][$key] = $k + 1;
                    continue;
                }
                if ($count == 1) {
                    if (isset($table['callBack'])) {
                        $lastD[$k][$key] = $table['callBack']($dat->{$str[0]});
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
                    if (isset($table['callBack'])) {
                        $lastD[$k][$key] = $table['callBack']($dat);
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

    public function render()
    {
        if ($this->columnAction != null)
            array_push($this->table, ['columnAction' => $this->columnAction]);

        return ['table' => $this->table, 'button' => $this->button, 'navbar' => $this->navbar, 'data' => $this->data];
    }

    public function createButton($button)
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if (!is_array($button) && $button == 'auto') {
            $this->button = array([
                'url' => route($model . '.create'),
                'text' => config('sledge.index.addLinkText'),
                'icon' => config('sledge.index.addLinkIcon'),
            ]);
            return 0;
        }

        foreach ($button as $key => $btn) {
            $this->button[$key] = [
                'url' => $btn[0],
                'text' => $btn[1],
                'icon' => $btn[2],
            ];
        }
    }

    public function createNavbar($navbar)
    {
        if (!is_array($navbar) && $navbar == 'auto') {
            $navbarConfig = Helper::routePrefix(request()->route()->getName());
            $this->navbar = [
                '<i class="bx bx-home-alt"></i>' => config('sledge.route.defaultRoute'),
                $this->module => 'blog.index',
                $navbarConfig[1] => request()->route()->getName()
            ];
            /*switch ($navbarConfig[0]) {
                case 'index':
                    $this->navbar = [
                        '<i class="bx bx-home-alt"></i>' => config('sledge.route.defaultRoute'),
                        $this->module => 'blog.index',
                        $navbarConfig[1] => request()->route()->getName()
                        ];
                    break;
                case 'create':
                    $this->navbar = [
                        '<i class="bx bx-home-alt"></i>' => config('sledge.route.defaultRoute'),
                        $this->module => 'blog.index',
                        $navbarConfig[1] => request()->route()->getName()
                    ];
                    break;
                case 'edit':
//                    dd('edit');
                    break;
                default:
//                    dd('DEFAULT');

            }*/
        }

    }

    public function formConfig($form = 'auto')
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if ($form == 'auto'){
            $formConfig = Helper::routePrefix(request()->route()->getName());

            if ($formConfig[0] == 'create'){
                $this->formMethod = "POST";
                $this->formMethodField = "POST";
                $this->formAction = $model .'.store';
            }
            if ($formConfig[0] == 'edit'){
                $this->formMethod = "POST";
                $this->formMethodField = "PATCH";
                $this->formAction = $model .'.update';
            }
        }else{
            $this->formMethod = $form[0];
            $this->formMethodField = $form[1];
            $this->formAction = $form[2];
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

}

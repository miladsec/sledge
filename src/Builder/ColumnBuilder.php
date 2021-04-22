<?php

namespace MiladZamir\Sledge\Builder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use MiladZamir\Sledge\Helper\Helper;
use Morilog\Jalali\Jalalian;

class ColumnBuilder
{
    private $metaData = [];
    private $navLink;
    private $confirm;


    private $model;
    private $route;
    private $value;
    private $table = [];
    private $columnAction = [];
    private $searchAttributes = [];

    //$condition = null, $addButton = null, $confirm = null,$navLink = null
    public function __construct($model, $route)
    {
        $this->model = app($model);
        $this->route = $route;

//        if ($addButton!= null)
//            self::createAddButton($addButton, $model);
//
//        if ($confirm!= null)
//            self::createConfirm($confirm);
//
//        if ($navLink!= null){
//            $navLink = new NavLinkBuilder($navLink);
//            $this->navLink = $navLink->create();
//        }
    }

    public function queryConfig($orderBy = null,$where = null, $whereIn = null)
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
                    foreach ($table['columnAction'] as $ca){
                        $routeVariables = [];
                        foreach ($ca['variables'] as $variable) {
                            foreach ($variable as $var=>$v){
                                $routeVariables += [$var => $dat->{$v}];
                            }
                        }
                        $route = route($ca['routeName'], $routeVariables);
                        $routeString = config('sledge.columnAction.route');
                        $routeString = str_replace('*1',$ca['class'], $routeString);
                        $routeString = str_replace('*2',$route, $routeString);
                        $routeString = str_replace('*3',$ca['icon'], $routeString);
                        $routeString = str_replace('*4',$ca['title'], $routeString);
                        $lastD[$k][$key] = str_replace('*1',$routeString, config('sledge.columnAction.static'));
                    }
                    continue;
                }

                $str = explode('.', $table['name']);
                $count = count($str);
                if ($table['name'] == '#'){
                    $lastD[$k][$key] = $k+1;
                    continue;
                }
                if ($count == 1) {
                    if (isset($table['callBack'])){
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
                    if (isset($table['callBack'])){
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

        return ['table' => $this->table, 'metaData' => $this->metaData, 'navLink' => $this->navLink, 'confirm' => $this->confirm];
    }

    public function createAddButton($metaData, $model)
    {
        $model = lcfirst(Helper::getModel($model));
        if ($metaData == 'auto') {
//            if (Helper::getActionStatus(url()->current(), $model))
                $this->metaData = array([
                    'url' => route($model . '.create'),
                    'text' => config('sledge.index.addLinkText'),
                    'icon' => config('sledge.index.addLinkIcon'),
                ]);
        } else {
//            if (Helper::getActionStatus(url()->current(), $model)) {
                foreach ($metaData as $key=>$meta){
                    $this->metaData[$key] = [
                        'url' => $meta[0],
                        'text' => $meta[1],
                        'icon' => $meta[2],
                    ];
                }
//            }

        }
    }

    public function createConfirm($confirm)
    {
        $this->confirm = [
            'selector' => $confirm[0],
            'script' => $confirm[1]
        ];
    }
}

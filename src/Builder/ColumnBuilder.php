<?php

namespace MiladZamir\Sledge\Builder;

use MiladZamir\Sledge\Helper\Helper;

class ColumnBuilder
{
    private $table = [];
    private $metaData = [];
    private $columnAction = [];
    private $navLink;
    private $model;
    private $value;
    private $confirm;

    public function __construct($model, $condition = null, $addButton = null, $confirm = null,$navLink = null)
    {
        $this->model = app($model);
        $this->value = $this->model->where($condition);

        if ($addButton!= null)
            self::createAddButton($addButton, $model);

        if ($confirm!= null)
            self::createConfirm($confirm);

        if ($navLink!= null){
            $navLink = new NavLinkBuilder($navLink);
            $this->navLink = $navLink->create();
        }
    }

    public function column($name, $text)
    {
        $data = [
            'name' => $name,
            'text' => $text,
        ];
        array_push($this->table, $data);
    }
//$name, $text, $action = false, $meta, $method = null
    public function columnAction($action, $variable, $key, $title, $icon, $class = null)
    {
        $data = [
            'action' => $action,
            'variable' => $variable,
            'key' => $key,
            'title' => $title,
            'icon' => $icon,
            'class' => ($class != null) ? implode(' ', $class) : '',
        ];
        array_push($this->columnAction, $data);
    }

    public function render()
    {
        if ($this->columnAction != null)
            array_push($this->table, ['columnAction' => $this->columnAction]);

        return ['table' => $this->table, 'metaData' => $this->metaData, 'navLink' => $this->navLink, 'confirm' => $this->confirm];
    }

    public function getDataTable($request)
    {
        $mmx = $this->value->count();

        $start = (int)$request->input('start');

        $length = (int)$request->input('length');

        $data = $this->value->skip($start)->take($length)->get();

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
                    $res = '';
                    foreach ($table['columnAction'] as $ca){
                        $route = route($ca['action'], [$ca['variable'] => $dat->{$ca['key']}]);
                        $res .= '<a class="dropdown-item '. $ca['class'] .'" href="' . $route . '"><i class="' . $ca['icon'] . ' mr-1"></i>' . $ca['title'] . '</a>';
                        $lastD[$k][$key] = '<div class="dropdown">
                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                <div class="dropdown-menu">' . $res . '</div></div>';
                    }
                    continue;
                }

                $str = explode('.', $table['name']);
                $count = count($str);
                if (isset($table['action'])) {
                    $res = '';
                    foreach ($table['meta'] as $meta) {
                        $route = route($meta[0], [$meta[1] => $dat->{$meta[2]}]);
                        if (!isset($meta[5]))
                            $meta[5] = '';
                        $res .= '<a class="dropdown-item '. $meta[5] .'" href="' . $route . '"><i class="' . $meta[4] . ' mr-1"></i>' . $meta[3] . '</a>';
                    }
                    $lastD[$k][$key] = '<div class="dropdown">
                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                <div class="dropdown-menu">' . $res . '</div></div>';
                    continue;
                } elseif ($table['name'] == '#'){
                    $lastD[$k][$key] = $k+1;
                    continue;
                } elseif ($count == 1) {
                    $lastD[$k][$key] = $dat->{$str[0]};
                    continue;
                } else {
                    for ($i = 0; $i < count($str); $i++) {
                        $dat = $dat->{$str[$i]};
                        $lastD[$k][$key] = $dat;
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
            "recordsFiltered" => $mmx,
        ]);
    }

    public function createAddButton($metaData, $model)
    {
        $model = lcfirst(Helper::getModel($model));
        if ($metaData == 'auto') {
            if (Helper::getActionStatus(url()->current(), $model))
                $this->metaData = [
                    'url' => route($model . '.create'),
                    'text' => config('sledge.index.addLinkText'),
                    'icon' => config('sledge.index.addLinkIcon'),
                ];
        } else {
            if (Helper::getActionStatus(url()->current(), $model))
                $this->metaData = [
                    'url' => $metaData[0],
                    'text' => $metaData[1],
                    'icon' => $metaData[2],
                ];
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

<?php


namespace MiladZamir\Sledge\Builder;

use Illuminate\Support\Str;
use MiladZamir\Sledge\Helper\Helper;

class Config
{
    public $model;
    public $value;
    public $searchAttributes = [];
    public $module;
    public $formMethod;
    public $formMethodField;
    public $formAction;
    public $button;
    public $breadcrumb;
    public $editData;
    public $moduleName;
    public $dataTableSource;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param bool $auto
     * @param $data
     * @return $this
     */
    public function form(bool $auto = true, $data = null): Config
    {
        $model = lcfirst(class_basename($this->model));

        if ($auto && empty($data)){
            $requestRoute = request()->route()->getName();
            if (str_contains($requestRoute, '.create') || str_contains($requestRoute, '.index')){
                $this->formMethod = "POST";
                $this->formMethodField = "POST";
                $this->formAction = route($model .'.store');
            }
            if (str_contains($requestRoute, '.edit')){
                $this->formMethod = "POST";
                $this->formMethodField = "PATCH";
                $this->formAction = route($model .'.update', [$model => request()->route($model)]);
            }
        }else{
            $this->formMethod = $data[0];
            $this->formMethodField = $data[1];
            $this->formAction = $data[2];
        }

        return $this;
    }

    public function queryConfig($where = null, $whereIn = null, $orderBy = "id DESC", $distinct= null, $select = null, $groupBy=null, $sumColumn=null): Config
    {
        $this->value = $this->model;

        if ($orderBy != null)
            $this->value = $this->model->orderByRaw($orderBy);

        if ($where != null)
            $this->value = $this->model->where($where);

        if ($whereIn != null)
            $this->value = $this->model->whereIn($whereIn[0], $whereIn[1]);

        if ($distinct != null)
            $this->value = $this->model->distinct();

        if ($select != null) {
            $this->value = $this->model->select($select);
        }

        // If $sumColumn is provided, add the sum of that column to the select clause
        if ($sumColumn != null) {
            $this->value = $this->value->addSelect(\DB::raw('SUM(' . $sumColumn . ') as ' . $sumColumn));
        }

        if ($groupBy != null) {
            // Check if $groupBy is an array and use each column in the groupBy clause
            if (is_array($groupBy)) {
                foreach ($groupBy as $column) {
                    $this->value = $this->value->groupBy($column);
                }
            } else {
                $this->value = $this->value->groupBy($groupBy);
            }
        }

        return $this;
    }

    public function dataTableConfig($searchAttributes = []): Config
    {
        $this->searchAttributes = $searchAttributes;
        return $this;
    }

    public function dataTableSource($source = null): Config
    {
        $this->dataTableSource = $source;
        return $this;
    }

    public function pageConfig($moduleName, $button = 'auto', $breadcrumb = 'auto'): Config
    {
        $this->moduleName = $moduleName;

        if($button)
            $this->createButton($button);

        $this->createBreadcrumb($breadcrumb);

        return $this;
    }

    public function editData($editData)
    {
        $this->editData = $editData;
        return $this;
    }

    public function createButton($button): Config
    {
        $model = lcfirst(class_basename($this->model));

        if (!is_array($button) && $button == 'auto') {

            $currentRouteName = request()->route()->getName();
            if (Str::endsWith($currentRouteName, 'index')) {
                $createRoute = str_replace('index', 'create', $currentRouteName);
                $routeParameters = request()->route()->parameters();
                $createUrl = route($createRoute, $routeParameters ?? null);
                // Use $createUrl for redirection or other purposes
            }else{
                $createUrl = route($model . '.create');
            }

            $this->button = array([
                'url' => $createUrl,
                'text' => config('sledge.index.addLinkText'),
                'icon' => config('sledge.index.addLinkIcon'),
            ]);
            return $this;
        }
        foreach ($button as $key => $btn) {
            $this->button[$key] = [
                'url' => $btn[0],
                'text' => $btn[1],
                'icon' => $btn[2],
            ];
        }

        return $this;
    }

    public function createBreadcrumb($breadcrumb): Config
    {
        $model = lcfirst(class_basename($this->model));

        if (!is_array($breadcrumb) && $breadcrumb == 'auto') {
            $routeName = request()->route()->getName();

            switch ($routeName){
                case $model.'.index':
                    $this->breadcrumb = [
                        config('sledge.route.homeRouteTitle') => route(config('sledge.route.homeRouteName')),
                        $this->moduleName => route($model.'.index'),
                    ];
                    break;
                case $model.'.create':
                    $this->breadcrumb = [
                        config('sledge.route.homeRouteTitle') => route(config('sledge.route.homeRouteName')),
                        $this->moduleName => route($model.'.index'),
                        'افزودن' => route($model.'.create'),
                    ];
                    break;
                case $model.'.edit':
                    $this->breadcrumb = [
                        config('sledge.route.homeRouteTitle') => route(config('sledge.route.homeRouteName')),
                        $this->moduleName => route($model.'.index'),
                        'ویرایش' => route($model.'.edit', [$model => request()->route($model)])
                    ];
                    break;
            }
        }else{
            $this->breadcrumb = [config('sledge.route.homeRouteTitle') => route(config('sledge.route.homeRouteName'))] + $breadcrumb;
        }
        return $this;
    }


}

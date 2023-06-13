<?php


namespace MiladZamir\Sledge\Builder;

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
            if (str_contains($requestRoute, '.create')){
                $this->formMethod = "POST";
                $this->formMethodField = "POST";
                $this->formAction = route($model .'.store');
            }
            if (str_contains($requestRoute, '.edit')){
                $this->formMethod = "POST";
                $this->formMethodField = "PATCH";
                $this->formAction = route($model .'.update', [$model => request()->route('blog')]);
            }
        }else{
            $this->formMethod = $data[0];
            $this->formMethodField = $data[1];
            $this->formAction = $data[2];
        }

        return $this;
    }

    public function queryConfig($orderBy = "id DESC", $where = null, $whereIn = null): Config
    {
        $this->value = $this->model;

        if ($orderBy != null)
            $this->value = $this->model->orderByRaw($orderBy);

        if ($where != null)
            $this->value = $this->model->where($where);

        if ($whereIn != null)
            $this->value = $this->model->whereIn($whereIn[0], $whereIn[1]);

        return $this;
    }

    public function dataTableConfig($searchAttributes = []): Config
    {
        $this->searchAttributes = $searchAttributes;
        return $this;
    }

    public function pageConfig($module, $button = 'auto', $breadcrumb = 'auto'): Config
    {
        $this->module = $module;

        $this->createButton($button);
        $this->createBreadcrumb($breadcrumb);

        return $this;
    }


    public function createButton($button): Config
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if (!is_array($button) && $button == 'auto') {
            $this->button = array([
                'url' => route($model . '.create'),
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
        $model = lcfirst(Helper::getModel($this->modelName));

        if (!is_array($breadcrumb) && $breadcrumb == 'auto') {
            $breadcrumbConfig = Helper::routePrefix(request()->route()->getName());

            if ($breadcrumbConfig[0] == 'edit'){
                $this->breadcrumb = [
                    '<i class="bx bx-home-alt"></i>' => route(config('sledge.route.defaultRoute')),
                    $this->module => route('blog.index'),
                    $breadcrumbConfig[1] => route($model.'.edit', [$model => request()->route('blog')])
                ];
                return $this;
            }
            $this->breadcrumb = [
                '<i class="bx bx-home-alt"></i>' => route(config('sledge.route.defaultRoute')),
                $this->module => route('blog.index'),
                $breadcrumbConfig[1] => route(request()->route()->getName())
            ];
        }
        return $this;
    }


}

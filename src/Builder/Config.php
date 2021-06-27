<?php


namespace MiladZamir\Sledge\Builder;


use Illuminate\Http\Request;
use MiladZamir\Sledge\Helper\Helper;

class Config
{
    public $config;
    public $model;
    public $value;
    public $searchAttributes = [];
    public $module;
    public $modelName;
    public $formMethod;
    public $formMethodField;
    public $formAction;

    public function __construct($config, $model, $modelName)
    {
        $this->config = $config;
        $this->model = $model;
        $this->modelName = $modelName;
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

        return $this;
    }

    public function dataTableConfig($searchAttributes = [])
    {
        $this->searchAttributes = $searchAttributes;
        return $this;
    }

    public function pageConfig($module, $button = 'auto', $breadcrumb = 'auto')
    {
        $this->module = $module;

        $this->createButton($button);
        $this->createBreadcrumb($breadcrumb);

        return $this;
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

    public function createBreadcrumb($breadcrumb)
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if (!is_array($breadcrumb) && $breadcrumb == 'auto') {
            $breadcrumbConfig = Helper::routePrefix(request()->route()->getName());

            if ($breadcrumbConfig[0] == 'edit'){
                $this->breadcrumb = [
                    '<i class="bx bx-home-alt"></i>' => route(config('sledge.route.defaultRoute')),
                    $this->module => route('blog.index'),
                    $breadcrumbConfig[1] => route($model.'.edit', [$model => \request()->route('blog')])
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

    public function formConfig($form = 'auto'): Config
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if ($form == 'auto'){
            $formConfig = Helper::routePrefix(request()->route()->getName());
            if ($formConfig[0] == 'create'){
                $this->formMethod = "POST";
                $this->formMethodField = "POST";
                $this->formAction = route($model .'.store');
            }
            if ($formConfig[0] == 'edit'){
                $this->formMethod = "POST";
                $this->formMethodField = "PATCH";
                $this->formAction = route($model .'.update', [$model => \request()->route('blog')]);
            }
        }else{
            $this->formMethod = $form[0];
            $this->formMethodField = $form[1];
            $this->formAction = $form[2];
        }

        return $this;
    }

}

<?php


namespace MiladZamir\Sledge\Builder;


use MiladZamir\Sledge\Helper\Helper;

class Config
{
    public $config;
    public $model;
    public $value;
    public $searchAttributes = [];
    public $module;
    public $modelName;

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

    public function pageConfig($module, $button = 'auto', $navbar = 'auto')
    {
        $this->module = $module;

        $this->createButton($button);
        $this->createNavbar($navbar);

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

    public function createNavbar($navbar)
    {
        $model = lcfirst(Helper::getModel($this->modelName));

        if (!is_array($navbar) && $navbar == 'auto') {
            $navbarConfig = Helper::routePrefix(request()->route()->getName());

            if ($navbarConfig[0] == 'edit'){
                $this->navbar = [
                    '<i class="bx bx-home-alt"></i>' => config('sledge.route.defaultRoute'),
                    $this->module => 'blog.index',
                    $navbarConfig[1] => $model.'.edit'
                ];
                return $this;
            }
            $this->navbar = [
                '<i class="bx bx-home-alt"></i>' => config('sledge.route.defaultRoute'),
                $this->module => 'blog.index',
                $navbarConfig[1] => request()->route()->getName()
            ];
        }
        return $this;
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

        return $this;
    }

}

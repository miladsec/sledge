<?php

namespace MiladZamir\Sledge\Builder;

use Illuminate\Http\JsonResponse;

class Builder
{
    private $model;
    public array $table = [];
    private array $actions = [];
    public $config;
    public $form;
    public array $formData = [
        'header' => [],
        'body' => [],
        'footer' => []
    ];
    private $script;

    public function __construct($model)
    {
        $this->model = app($model);
    }

    public function config(): Config
    {
        $this->config = new Config($this->model);
        return $this->config;
    }

    public function form(): Form
    {
        $this->form[] = new Form($this->config);
        return end($this->form);
    }

    public function column(): Column
    {
        $this->table[] = new Column();
        return end($this->table);
    }


    public function script($scriptFile): void
    {
        $script = new Script($scriptFile);
        $this->script = $script->scriptFile;
    }

    public function createDataTable($request): JsonResponse
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
                if ($table->isAction){
                    $routeStrings = '';
                    foreach ($this->actions as $action){
                        $route = route($action->route, [$action->variable => $dat->{$action->key}]);

                        $cssClasses = isset($action->cssClass) ? implode(" ", $action->cssClass) : '';
                        $routeString = "<a href='$route' type='button' class='btn rounded-pill btn-icon $cssClasses'><span class='tf-icons bx $action->icon'></span></a>";
//                        $routeString = "<a href='$route' class='btn $cssClasses' data-bs-toggle='tooltip' data-bs-placement='top' title='$action->title'>
//                                            <i class='$action->icon'></i>
//                                        </a>";

                        $routeStrings .= $routeString;
                    }
                    $lastD[$k][$key] = $routeStrings;
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
        $data1 = $lastD;

        if ($data1[0][0] == null)
            $data1 = [];

        return response()->json([
            'data' => $data1,
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
        if (!empty($this->table) &&  is_array($this->table)){
            $haveAction = false;
            foreach ($this->table as $key=>$table){
                if ($table->isAction){
                    array_push($this->actions, $table);
                    unset($this->table[$key]);
                    $haveAction = true;
                }
            }
            if ($haveAction){
//                array_push($this->table, $this->column()->isAction(true)->title(''));
                $this->table = array_values($this->table);
            }
            $this->column()->isAction(true)->title('');
        }
        return ['table' => $this->table, /*'button' => $this->config->button,*//* 'breadcrumb' => $this->config->breadcrumb,*/ 'form' => $this->formData, 'script' => $this->script];
    }

}

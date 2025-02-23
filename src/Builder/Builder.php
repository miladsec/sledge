<?php

namespace MiladZamir\Sledge\Builder;

use Illuminate\Http\JsonResponse;
use Morilog\Jalali\Jalalian;

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
        'sectionBody' => [],
        'footer' => []
    ];
    public array $script = [];

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
        $this->script[] = new Script($scriptFile);
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
                if ($table->isAction) {
                    $routeStrings = '';
                    foreach ($this->actions as $action) {
                        if (isset($action->uiComponent)) {
//                            return JsonResponse::create($action);
                            $name = $action->name;
                            $data = $action->uiComponentData;
                            $routeStrings = view('sledge::' . $action->uiComponent)->with(compact('dat','name', 'data'));

                            $routeStrings .= "<a type='button' class='btn rounded-pill btn-icon' data-bs-toggle='modal' data-bs-target='#modal-$dat->id'> 
                                                <span class='tf-icons bx $action->icon'></span></a>";
                            continue;
                        }
                        $route = route($action->route, [$action->variable => $dat->{$action->key}]);

                        $cssClasses = isset($action->cssClass) ? implode(" ", $action->cssClass) : '';
                        $routeString = "<a href='$route$action->queryString' type='button' class='btn rounded-pill btn-icon $cssClasses'><span class='tf-icons bx $action->icon'></span></a>";
//                        $routeString = "<a href='$route' class='btn $cssClasses' data-bs-toggle='tooltip' data-bs-placement='top' title='$action->title'>
//                                            <i class='$action->icon'></i>
//                                        </a>";

                        $routeStrings .= $routeString;
                    }
                    $lastD[$k][$key] = $routeStrings;
                    continue;
                }
                $str = explode('.', $table->name);
                $strDate = strpos($table->name, '_at');
                $count = count($str);
                if ($table->name == '#') {
                    $lastD[$k][$key] = $k + 1;
                    continue;
                }
                if(isset($table->view)){
                    $data = $dat;
                    $lastD[$k][$key] = view('sledge::'. $table->view)->with(compact('data'))->render();
                    continue;
                }
                if ($count == 1) {
                    if (!empty($table->callBack) && is_callable($table->callBack)) {
                        $lastD[$k][$key] = call_user_func($table->callBack, $dat->{$str[0]});
                        continue;
                    }
                    if ($strDate){

                        if(strpos($table->name, '_time_at')){
//                            return JsonResponse::create($dat->{$str[0]});
                            $lastD[$k][$key] = date('H:i', strtotime($dat->{$str[0]}));
                        }
                        else
                            $lastD[$k][$key] = Jalalian::forge($dat->{$str[0]}->timestamp)->format('h:i - %Y/%m/%d');

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
        if (!empty($this->form)) {
            foreach ($this->form as $form) {
                if (!empty($form->headerData)) {
                    array_push($this->formData['header'], $form->headerData);
                } elseif (!empty($form->bodyData)) {
                    array_push($this->formData['body'], $form->bodyData);
                } elseif (!empty($form->footerData)) {
                    array_push($this->formData['footer'], $form->footerData);
                }elseif (!empty($form->sectionBodyData)) {
                    array_push($this->formData['sectionBody'], $form->sectionBodyData);
                }
            }
        }

        if (!empty($this->table) && is_array($this->table)) {
            $haveAction = false;

            // Check for actions in the table
            foreach ($this->table as $key => $table) {
                if ($table->isAction) {
                    array_push($this->actions, $table);
                    unset($this->table[$key]);
                    $haveAction = true;
                }
            }

            // Only add the action column if an action exists
            if ($haveAction) {
                $this->table = array_values($this->table);  // Re-index the array after removing actions
                $this->column()->isAction(true)->title('');
            }
        }

        return [
            'table' => $this->table,
            'form' => $this->formData,
            'script' => $this->script
        ];
    }


}

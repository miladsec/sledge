<?php

namespace MiladZamir\Sledge\Builder;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
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

    private function getNestedValue($object, $key) {
        $keys = explode('.', $key);
        foreach ($keys as $k) {
            if (isset($object->{$k})) {
                $object = $object->{$k};
            } else {
                return null;
            }
        }
        return $object;
    }
    public function createDataTable($request): JsonResponse
    {
        $mmx = $this->config->value->count();
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');

        $data = $this->config->value;
        $searchValue = $request->search['value'] ?? null;

        $searchValue = $request->search['value'] ?? null;

        if (!empty($searchValue)) {
            $columns = Schema::getColumnListing($data->getModel()->getTable()); // Get all table columns
            $relations = []; // TODO:: update here

            $data->where(function ($query) use ($columns, $searchValue) {
                // Search in main table columns
                foreach ($columns as $index => $column) {
                    if ($index === 0) {
                        $query->where($column, 'LIKE', "%{$searchValue}%");
                    } else {
                        $query->orWhere($column, 'LIKE', "%{$searchValue}%");
                    }
                }
            });

            // Search in related tables
            foreach ($relations as $relation) {
                $data->orWhereHas($relation, function ($subQuery) use ($searchValue) {
                    // Get columns of the related table
                    $relatedColumns = Schema::getColumnListing($subQuery->getModel()->getTable());

                    // Ensure at least one column filters data
                    $subQuery->where(function ($subQ) use ($relatedColumns, $searchValue) {
                        foreach ($relatedColumns as $index => $column) {
                            if ($index === 0) {
                                $subQ->where($column, 'LIKE', "%{$searchValue}%");
                            } else {
                                $subQ->orWhere($column, 'LIKE', "%{$searchValue}%");
                            }
                        }
                    });
                });
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
                    // Initialize the route string container
                    $routeStrings = '';

                    // Iterate through all actions
                    foreach ($this->actions as $action) {
                        // Check if the action has a UI component
                        if (isset($action->uiComponent)) {
                            $name = $action->name;
                            $data = $action->uiComponentData;

                            // Render the UI component view with data
                            $routeStrings = view('sledge::' . $action->uiComponent)->with(compact('dat', 'name', 'data'));

                            // Add the modal action button for the UI component
                            $routeStrings .= "<a type='button' class='btn rounded-pill btn-icon' data-bs-toggle='modal' data-bs-target='#modal-$dat->id'> 
                                <span class='tf-icons bx $action->icon'></span></a>";
                            continue;  // Skip to the next action after processing UI component
                        }

                        // Handle route with multiple variables
                        if (!empty($action->variables)) {
                            // Initialize the array for route variables
                            $routeVariables = [];

                            // Iterate through the action variables and resolve them using dot notation
                            foreach ($action->variables as $varName => $varKey) {
                                // Check if dot notation is used to access related fields
                                $routeVariables[$varName] = $this->getNestedValue($dat, $varKey);
                            }

                            // Generate the route with the array of variables
                            $route = route($action->route, $routeVariables);
                        } else {
                            // If no variables, generate the route normally
                            $route = route($action->route);
                        }

                        // Append the query string if provided in the action
                        $route .= !empty($action->queryString) ? $action->queryString : '';

                        // Build the action button HTML
                        $cssClasses = isset($action->cssClass) ? implode(" ", $action->cssClass) : '';

                        $alertCustomDetailMessage = '';
                        if ($action->alertCustomDetailMessage){
                            $alertCustomDetailMessage = $action->alertCustomDetailMessage;
                        }

                        $routeString = "<a data-bs-toggle='tooltip' date-alert-custom-detail-message='$alertCustomDetailMessage' title='$action->title' data-bs-placement='top' data-bs-html='true' href='$route' type='button' class='btn rounded-pill btn-icon $cssClasses'>
                            <span class='tf-icons bx $action->icon'></span>
                        </a>";

                        // Append the generated button to the list of actions
                        $routeStrings .= $routeString;
                    }

                    // Store the final route string for later use
                    $lastD[$k][$key] = $routeStrings;
                    continue;  // Proceed to the next table row
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
                        else{
                            if (!empty($dat->{$str[0]}->timestamp)){
                                $lastD[$k][$key] = Jalalian::forge($dat->{$str[0]}->timestamp)->format('h:i - %Y/%m/%d');
                            }else{
                                $lastD[$k][$key] = '';
                            }
                        }

                        continue;
                    }

                    $lastD[$k][$key] = $dat->{$str[0]};
                    continue;
                }
                if ($count > 1) {
                    foreach ($str as $relation) {
                        if (!isset($dat->{$relation})) {
                            $lastD[$k][$key] = '-';
                            continue 2; // Break out of both loops
                        }
                        $dat = $dat->{$relation};
                    }

                    // Apply callback if defined
                    if (!empty($table->callBack) && is_callable($table->callBack)) {
                        $lastD[$k][$key] = call_user_func($table->callBack, $dat);
                    } else {
                        $lastD[$k][$key] = $dat;
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

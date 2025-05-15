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
    private array $extras = [];
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
        $allData = $this->config->value;
        $totalRecords = $allData->get()->count();
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $searchValue = $request->search['value'] ?? null;

//        $query = clone $allData;

        // Handle ordering
        $orderCount = count($request->input('order', []));

        for ($i = 0; $i < $orderCount; $i++) {
            $orderColumnIndex = $request->input("order.$i.column");
            $orderDirection = $request->input("order.$i.dir", 'asc');
            $tableConfig = $this->table[$orderColumnIndex] ?? null;

            if ($tableConfig && isset($tableConfig->name) && !$tableConfig->isAction && !$tableConfig->isExtra && $tableConfig->name !== '#' && $tableConfig->name !== 'checkbox') {
                if (strpos($tableConfig->name, '.') === false) {
                    $allData = $allData->orderBy($tableConfig->name, $orderDirection);
                }
                // Optional: for nested relations (user.name), you can sort manually after fetching or by joining if needed
            }
        }

        if (!empty($searchValue)) {
            // Get the columns of the current table
            $columns = Schema::getColumnListing($allData->getModel()->getTable());

            // Define related models to search in (e.g. 'posts', 'comments', etc.)
            $relations = $this->config->searchAttributes ?? []; // You need to define relations here (e.g., 'user', 'category')

            $allData = $allData->where(function ($q) use ($columns, $searchValue) {
                // Search in all columns of the current model's table
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchValue}%");
                }
            });

            // Search through related models
            foreach ($relations as $relation) {
                $allData = $allData->orWhereHas($relation, function ($subQuery) use ($searchValue) {
                    $relatedColumns = Schema::getColumnListing($subQuery->getModel()->getTable());

                    $subQuery->where(function ($q) use ($relatedColumns, $searchValue) {
                        foreach ($relatedColumns as $column) {
                            $q->orWhere($column, 'LIKE', "%{$searchValue}%");
                        }
                    });
                });
            }

            // Get the filtered records count
            $filteredRecords = $allData->count();

            // Paginate the results
        } else {
            // When no search value is provided, return the paginated data without filtering
            $filteredRecords = $totalRecords;
        }
        $paginatedData = $allData->skip($start)->take($length)->get();

        $page = max((int) floor($start / $length) + 1, 1);
        $request->request->add(['page' => $page]);

        $formattedData = [];
        $extra = '';
        foreach ($paginatedData as $index => $row) {
            $originalRow = clone $row;
            $rowData = [];

            foreach ($this->table as $colIndex => $tableConfig) {
                if ($tableConfig->isAction) {
                    $actionsHtml = '';

                    foreach ($this->actions as $action) {
                        if (isset($action->uiComponent)) {
                            $actionsHtml .= view('sledge::' . $action->uiComponent)
                                ->with(['dat' => $row, 'name' => $action->name, 'data' => $action->uiComponentData])
                                ->render();
                            $actionsHtml .= "<a type='button' class='btn rounded-pill btn-icon' data-bs-toggle='modal' data-bs-target='#modal-$row->id'>
                                            <span class='tf-icons bx $action->icon'></span></a>";
                            continue;
                        }

                        $route = !empty($action->variables)
                            ? route($action->route, $this->resolveRouteVariables($row, $action->variables))
                            : route($action->route);

                        $route .= $action->queryString ?? '';
                        $css = implode(' ', $action->cssClass ?? []);
                        $tooltip = $action->alertCustomDetailMessage ?? '';

                        $actionsHtml .= "<a data-bs-toggle='tooltip' date-alert-custom-detail-message='$tooltip' title='$action->title' data-bs-placement='top' data-bs-html='true' href='$route' type='button' class='btn rounded-pill btn-icon $css'>
                                        <span class='tf-icons bx $action->icon'></span>
                                     </a>";
                    }

                    $rowData[$colIndex] = $actionsHtml;
                    continue;
                }

                if ($tableConfig->isExtra) {
                    $actionsHtml = '';

                    foreach ($this->extras as $action) {
                        if (isset($action->uiComponent)) {
                            $actionsHtml .= view('sledge::' . $action->uiComponent)
                                ->with(['dat' => $row, 'name' => $action->name, 'data' => $action->uiComponentData])
                                ->render();
                            $actionsHtml .= "<a type='button' class='btn rounded-pill btn-icon' data-bs-toggle='modal' data-bs-target='#modal-$row->id'>
                                            <span class='tf-icons bx $action->icon'></span></a>";
                            continue;
                        }

                        $route = !empty($action->variables)
                            ? route($action->route, $this->resolveRouteVariables($row, $action->variables))
                            : route($action->route);

                        $route .= $action->queryString ?? '';
                        $css = implode(' ', $action->cssClass ?? []);
                        $tooltip = $action->alertCustomDetailMessage ?? '';

                        $actionsHtml .= "<a data-bs-toggle='tooltip' date-alert-custom-detail-message='$tooltip' title='$action->title' data-bs-placement='top' data-bs-html='true' href='$route' type='button' class='btn rounded-pill btn-icon group-actions $css'>
                                        <span class='tf-icons bx $action->icon'></span>
                                     </a>";
                    }

                    $extra =  $actionsHtml;
                    continue;
                }

                $fieldParts = explode('.', $tableConfig->name);
                $isDateField = strpos($tableConfig->name, '_at') !== false;

                if ($tableConfig->name === '#') {
//                    $rowData[$colIndex] = $index + 1;
                    $rowData[$colIndex] = $row->id;
                    continue;
                }

                if ($tableConfig->name === 'checkbox') {
                    $rowData[$colIndex] =  '<div class="form-check">
                                        <input class="form-check-input row-checkbox" value="'. $row->id .'" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>';
                    continue;
                }

                if (isset($tableConfig->view)) {
                    $rowData[$colIndex] = view('sledge::' . $tableConfig->view)
                        ->with(['data' => $originalRow])
                        ->render();
                    continue;
                }

                $value = $this->resolveNestedField($row, $fieldParts);

                if (is_null($value)) {
                    $rowData[$colIndex] = '-';
                    continue;
                }

                if (!empty($tableConfig->callBack) && is_callable($tableConfig->callBack)) {
                    $rowData[$colIndex] = call_user_func($tableConfig->callBack, $value);
                } elseif ($isDateField) {
                    $rowData[$colIndex] = strpos($tableConfig->name, '_time_at') !== false
                        ? date('H:i', strtotime($value))
                        : (!empty($value->timestamp) ? Jalalian::forge($value->timestamp)->format('h:i - %Y/%m/%d') : '');
                } else {
                    $rowData[$colIndex] = $value;
                }

                $row = $originalRow; // Reset for the next column
            }

            $formattedData[] = $rowData;
        }

        return response()->json([
            'data' => $formattedData,
            'extra' => $extra,
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ]);
    }

    private function resolveRouteVariables($row, $variables): array
    {
        $resolved = [];
        foreach ($variables as $name => $path) {
            $resolved[$name] = $this->getNestedValue($row, $path);
        }
        return $resolved;
    }

    private function resolveNestedField($data, array $parts)
    {
        foreach ($parts as $part) {
            if (!isset($data->{$part})) {
                return null;
            }
            $data = $data->{$part};
        }
        return $data;
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

            $haveExtra = false;

            // Check for actions in the table
            foreach ($this->table as $key => $table) {
                if ($table->isExtra) {
                    array_push($this->extras, $table);
                    unset($this->table[$key]);
                    $haveExtra = true;
                }
            }

            // Only add the action column if an action exists
            if ($haveExtra) {
                $this->table = array_values($this->table);  // Re-index the array after removing actions

                $this->column()->isExtra(true)->title('');
            }
        }

        return [
            'table' => $this->table,
            'form' => $this->formData,
            'script' => $this->script
        ];
    }


}

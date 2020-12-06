<?php

namespace MiladZamir\Sledge\Builder;

use Illuminate\Http\Request;
use MiladZamir\Sledge\Helper\Helper;
use MiladZamir\Sledge\Helper\FormConfig;
use App\Category;

class ColumnBuilder
{
    private $table = [];
    private $model;
    private $value;
    private $data;

    public function __construct($model, $condition = null)
    {
        $this->model = app($model);
        $this->value = $this->model->where($condition);
    }

    public function column($name, $text)
    {
        $data = [
            'name' => $name,
            'text' => $text,
        ];
        array_push($this->table, $data);
    }

    public function columnAction($name, $text, $action = false, $meta,$method = null)
    {
        $data = [
            'name' => $name,
            'text' => $text,
            'action' => $action,
            'meta' => $meta,
            'method' => $method,
        ];
        array_push($this->table, $data);
    }

    public function render()
    {
        return $this->table;
    }

    public function getDataTable($request)
    {
        $mmx = $this->value->count();

        $start = (int) $request->input('start');

        $length = (int) $request->input('length');

        $data =  $this->value->skip($start)->take($length)->get();

        $page = ($start / $length) + 1;
        if (empty($page))
            $page = 1;

        if($page < 0)
            $page = 1;

        $request->request->add(['page'=>$page]);

        $lastD[][] = null;
        foreach ($data as $k=>$dat) {
            $secData = clone $dat;
            foreach ($this->table as $key => $table) {
                $str = explode('.', $table['name']);
                $count = count($str);
                if (isset($table['action'])) {
                    $res = '';
                    foreach ($table['meta'] as $meta){
                        $route = route($meta[0], [$meta[1] => $dat->{$meta[2]}]);
                        $res .=  '<a class="dropdown-item" href="'.$route.'"><i class="'. $meta[4] .' mr-1"></i>'. $meta[3] .'</a>';
                    }
                    $lastD[$k][$key] = '<div class="dropdown">
                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                <div class="dropdown-menu">' . $res .'</div></div>';
                    continue;
                } elseif ($count == 1) {
                    $lastD[$k][$key] = $dat->{$str[0]};
                    continue;
                }else{
                    for ($i = 0; $i < count($str); $i++) {
                        $dat = $dat->{$str[$i]};
                        $lastD[$k][$key] =  $dat;
                    }
                }
                $dat = $secData;
            }
        }
        $this->data = $lastD;

        return response()->json([
            'data' => $this->data,
            "draw"            => $request->input('draw'),
            "recordsTotal"    => $mmx,
            "recordsFiltered" => $mmx,
        ]);
    }
}

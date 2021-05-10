<?php


namespace MiladZamir\Sledge\Processor;


use MiladZamir\Sledge\Helper\Helper;

class Processor
{
    public $model;
    public $modelName;
    public $request;
    public $result;
    public $data;

    public function __construct($model, $request, $data = null)
    {
        $this->model = app($model);
        $this->modelName = $model;
        $this->request = $request;
        $this->data = $data;
    }

    public function create(): Processor
    {
        try {
            $result = $this->model->create($this->request->all());
            if ($result)
                $this->result = true;
            else
                $this->result = false;
        }catch (\Exception $e){
            $this->result = false;
        }
        return $this;
    }
    public function update(): Processor
    {
        try {
            $result = $this->data->update($this->request->all());
            if ($result)
                $this->result = true;
            else
                $this->result = false;
        }catch (\Exception $e){
            $this->result = false;
        }
        return $this;
    }

    public function delete(): Processor
    {
        try {
            $result = $this->data->delete();
            if ($result)
                $this->result = true;
            else
                $this->result = false;
        }catch (\Exception $e){
            $this->result = false;
        }
        return $this;
    }

    public function returnBack($haveAlert = true): \Illuminate\Http\RedirectResponse
    {
        if ($this->result){
            if ($haveAlert)
                Helper::flashMessage('success',config('sledge.alert.success'));
        }else{
            if ($haveAlert)
                Helper::flashMessage('danger', config('sledge.alert.danger'));
        }
        return redirect()->route(strtolower(Helper::getModel($this->modelName)). '.index');
    }

}

<?php


namespace MiladZamir\Sledge\Processor;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use MiladZamir\Sledge\Helper\Helper;

class Processor
{
    public $model;
    public $modelName;
    public $request;
    public $operationResultStatus;
    public $data;
    public $operationResultMessage;

    public function __construct($model, $request, $isStoreUserAudit = true,$data = null)
    {
        $this->model = is_string($model) ? app($model) : $model;
        $this->modelName = is_string($model) ? $model : get_class($this->model);
        $this->request = $request;
        $this->data = $data;

        if($isStoreUserAudit){
            $this->storeUserAudit();
        }
    }

    public function storeUserAudit()
    {
        $this->request['user_id'] = Auth::user()->id;
    }

    public function requestModification($request)
    {
        $this->request = $request;
    }
    public function create()
    {
        try {
            $result = $this->model->create($this->request->all());
            if ($result){
                $this->operationResultStatus = true;
                return $result;
            }
            else
                $this->operationResultStatus = false;
        }catch (\Exception $e){
            $this->operationResultStatus = false;
            $this->operationResultMessage = $e->getMessage();
            return $this->operationResultStatus;
        }
    }
    public function update()
    {
        try {
            $result = $this->model->update($this->request->all());
            if ($result){
                $this->operationResultStatus = true;
                return $this->model;
            }
            else
                $this->operationResultStatus = false;
        }catch (\Exception $e){
            $this->operationResultStatus = false;
            $this->operationResultMessage = $e->getMessage();
            return $this->operationResultStatus;
        }
        return $this;
    }

    public function delete(): Processor
    {
        try {
            $result = $this->model->delete();
            if ($result)
                $this->operationResultStatus = true;
            else
                $this->operationResultStatus = false;
        }catch (\Exception $e){
            $this->operationResultStatus = false;
        }
        return $this;
    }

    public function returnBack($haveAlert = true): RedirectResponse
    {
        if ($this->operationResultStatus){
            if ($haveAlert)
                Helper::flashMessage('success',config('sledge.alert.success'));
        }else{
            dd($this->operationResultMessage);
            if ($haveAlert)
                Helper::flashMessage('error', config('sledge.alert.danger'), $this->operationResultMessage);
        }

        return redirect()->route(lcfirst(Helper::getModel($this->modelName)). '.index');
    }

}

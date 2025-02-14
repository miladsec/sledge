<?php


namespace MiladZamir\Sledge\Processor;


use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use MiladZamir\Sledge\Helper\Helper;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Processor
{
    public $model;
    public $modelName;
    public $request;
    public $operationResultStatus;
    public $data;
    public $operationResultMessage;

    public $hasQrCode;
    public $qrCodeFileds;
    public $qrCode;
    public $qrCodeFor;

    public function __construct($model, $request, $isStoreUserAudit = true,$data = null)
    {
        $this->model = is_string($model) ? app($model) : $model;
        $this->modelName = is_string($model) ? $model : get_class($this->model);
        $this->request = $request;
        $this->data = $data;

        if($isStoreUserAudit){
            $this->storeUserAudit();
        }

        if(!empty($request))
            $this->requestHandler();
    }

    public function storeUserAudit()
    {
        $this->request['user_id'] = Auth::user()->id;
    }

    public function requestModification($request)
    {
        $this->request = $request;
    }

    public function validate($request, $validation)
    {
        $request->validate($validation);
    }

    public function qrCode($for, $fields, $result = null, $separator = '-')
    {
        $this->hasQrCode = true;

        if($result == null) {
            $this->qrCodeFileds = $fields;
            $this->qrCodeFor = $for;
        }else{
            $resultString = $for;
            $lastKey = array_key_last($fields);

            foreach ($fields as $key => $field) {
                $resultString .= $key . ':' . $result->{$field};
                if ($key !== $lastKey) {
                    $resultString .= '|';
                }
            }

            $this->qrCode = QrCode::size(120)->generate($resultString);

        }
        return $this;
    }

    public function storeQrCode($result)
    {
        $result->update(['qr_code' => $this->qrCode]);
    }

    public function create()
    {
        try {
            $result = $this->model->create($this->request->all());

            if ($result){
                $this->operationResultStatus = true;

                if ($this->hasQrCode){
                    $this->qrCode($this->qrCodeFor, $this->qrCodeFileds, $result);
                    $this->storeQrCode($result);
                }

                return $result;
            }
            else
                $this->operationResultStatus = false;
        }catch (\Exception $e){
            dd($e);
            $this->operationResultStatus = false;
            $this->operationResultMessage = $e->getMessage();
            return $this->operationResultStatus;
        }
    }
    public function createOrUpdate($searchKey = 'id')
    {
        try {
            $result = $this->model->updateOrCreate(
                [$searchKey => $this->request->all()[$searchKey]]
                ,$this->request->all());
            if ($result){
                $this->operationResultStatus = true;

                if ($this->hasQrCode){
                    $this->qrCode($this->qrCodeFor, $this->qrCodeFileds, $result);
                    $this->storeQrCode($result);
                }

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
    public function update($model=null)
    {
        try {
            $result = $model->update($this->request->all());
            if ($result){
                $this->operationResultStatus = true;
                if ($this->hasQrCode){
                    $this->qrCode($this->qrCodeFor, $this->qrCodeFileds, $model);
                    $this->storeQrCode($model);
                }
                return $this->model;
            }
            else{
                $this->operationResultStatus = false;
                return false;
            }
        }catch (\Exception $e){
            $this->operationResultStatus = false;
            $this->operationResultMessage = $e->getMessage();
            return $this->operationResultStatus;
        }
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
            if ($haveAlert)
                Helper::flashMessage('error', config('sledge.alert.danger'), $this->operationResultMessage);
        }

        return redirect()->route(lcfirst(Helper::getModel($this->modelName)). '.index');
    }

    private function requestHandler()
    {
        foreach ($this->request->all() as $key => $value) {
            if (str_ends_with($key, '_at')) {
                $newValue = $this->timestampToCarbon($value);
                $this->request->merge([$key => $newValue]);
            }
        }
    }

    private function timestampToCarbon($timestamp, $wantsTimestamp = false)
    {
        $timestamp = substr($timestamp, 0, -3);

        if ($wantsTimestamp)
            return $timestamp;

        return Carbon::createFromTimestamp($timestamp);
    }

}

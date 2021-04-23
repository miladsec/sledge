<?php

namespace MiladZamir\Sledge\Helper;

class Helper
{
    public static function createUniqueString($length)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    public static function getActionStatus($url, $text)
    {
        if (strpos($url, $text) !== false) {
            return true;
        }else{
            return false;
        }
    }

    public static function getModel($model)
    {
        $model = explode("\\", $model);
        return end($model);
    }

    public static function hasPermission($route)
    {
        if(auth()->check()){
            $user = auth()->user();

            try {
                if (empty($route) || $user->username == 'SuperAdmin' || $user->can($route)) {
                    return true;
                }
            }catch (\Exception $e){
                if(config('app.debug'))
                    throw new \Exception($e->getMessage());
                else
                    abort(500);
            }
        }
        return false;
    }

    public static function routePrefix($currentRoute)
    {
        foreach (config('sledge.route.namePrefix') as $name=>$route){
            if (str_contains($currentRoute, $name)){
                return [$name, $route];
            }
        }
    }
}

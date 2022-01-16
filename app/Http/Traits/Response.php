<?php

namespace App\Http\Traits;

trait Response
{
    public function successCode()
    {
        return [200, 201, 202];
    }
    public  function ResponseApi($message = '', $dataOrErrors = null, $code = 200, $meta = [])
    {
        $array = [
            'status' => in_array($code, $this->successCode()) ? true : false,
            'message' => ($message == null) ? '' : $message,
            in_array($code, $this->successCode()) ? 'data' : 'errors'  => $dataOrErrors,
        ];
        if (!empty($meta))
            foreach ($meta as $key => $value) {
                $array[$key] = $value;
            }

        return response($array, $code);
    }

}

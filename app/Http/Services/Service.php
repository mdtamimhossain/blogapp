<?php

namespace App\Http\Services;


class Service
{
    protected bool $success;
    protected string $message;
    protected array $data;

    /**
     * @param $data
     * @param $message
     * @return array
     */
    protected function responseSuccess(string $message="",array $data=[] ) : array
            {
               return ['success'=>true,'data'=>$data,'message'=>$message];
            }

    /**
     * @param $data
     * @param $message
     * @return array
     */
    protected function responseError( string $message="",array $data=[]) : array
    {
        return ['success'=>false,'message'=>$message];
    }

}

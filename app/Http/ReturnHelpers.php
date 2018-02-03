<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-2-3
 * Time: 21:24
 */

namespace App\Http;


class ReturnHelpers
{
    protected $code;

    protected $status;

    public function __construct($code = 200, $status = 'OK')
    {
        $this->code = $code;
        $this->status = $status;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function buildArray($data)
    {
        return [
            'data' => $data,
            'code' => $this->code,
            'status' => $this->status,
        ];
    }

    public static function returnWithStatus($data = null, $code = 200, $status = 'OK')
    {
        return [
            'data' => $data,
            'code' => $code,
            'status' => $status,
        ];
    }
}
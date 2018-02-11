<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-2-3
 * Time: 21:24
 */

namespace App\Http;

/**
 * Class ReturnHelper
 * @package App\Http
 */
class ReturnHelper
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

    /**
     * @param null $data
     * @param int $code
     * @param string $status
     * @return array
     */
    public static function returnWithStatus($data = null, $code = 200, $status = 'OK')
    {
        if(is_object($data)){
            return [
//                'data' => json_decode($data->toJSON(),true)['data'],
                'data' => json_decode($data->toJSON(),true),
                'code' => $code,
                'status' => $status,
            ];
        }

        if(is_array($data) && key_exists('errors',$data)){
            return [
                'errors' => $data['errors'],
                'code' => $code,
                'status' => $status,
            ];
        }

        return [
            'data' => $data,
            'code' => $code,
            'status' => $status,
        ];
    }
}

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
    public static function returnWithStatus($data = null, $code = 200, $paginator = null)
    {
        $to_return = ['code'=>$code];
        if(is_a($data,'\Cyvelnet\Laravel5Fractal\Adapters\ScopeDataAdapter')){
            $to_return['data'] = json_decode($data->toJSON(),true);
        }
        else{
            $to_return['data'] = $data;
        }


        if(null !== $paginator){

            $page_info = $paginator->toArray();
            unset($page_info['data']);

            $to_return = array_merge($to_return,$page_info);

            //由于paginator中提供的url中会将原url中的get方法的参数删掉，因此在这里加上，
            $to_return['first_page_url'] .= ('&cnt='.$to_return['per_page']);
            if($to_return['next_page_url'] !== null){
                $to_return['next_page_url'] .= ('&cnt='.$to_return['per_page']);
            }
            if($to_return['prev_page_url'] !== null){
                $to_return['prev_page_url'] .= ('&cnt='.$to_return['per_page']);
            }
        }

        return $to_return;
    }
}

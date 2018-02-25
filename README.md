# e曈网创意工坊

接口文档在docs文件夹下

安装好后要将
\League\Fractal\Serializer\ArraySerializer.php中的collection方法改写
(位于vendor/league/fractal/src/Serializer)
```php
    public function collection($resourceKey, array $data)
    {
        return $data;
        //原本如下行
//        return [$resourceKey ?: 'data' => $data];
    }
```

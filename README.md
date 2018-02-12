# e曈网创意工坊

[WebAPI](http://apizza.cc/console/project/c38ae5c7dc331862cb53dab487521c65/dev)

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

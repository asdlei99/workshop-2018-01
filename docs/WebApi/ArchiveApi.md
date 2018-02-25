# 文章类别
* 获取类别列表

## 获取类别列表
```html
GET {{server}}/archives
```

* 成功返回列表
    ```json
    {
        "code": 200,
        "data": [
            {
                "name": "干货",
                "id": 1,
                "parent_id": 0
            },
            {
                "name": "黑科技",
                "id": 2,
                "parent_id": 1
            },
            {
                "name": "教程",
                "id": 3,
                "parent_id": 1
            }
        ] 
    }
    ```
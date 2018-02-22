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
                "description": "干货",
                "id": 1
            },
            {
                "description": "黑科技",
                "id": 2
            },
            {
                "description": "教程",
                "id": 3
            }
        ]
    }
    ```
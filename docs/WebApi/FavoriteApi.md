# 收藏
* 收藏文章或取消收藏

## 收藏文章或取消收藏
```html
POST {{server}}/favorites/posts/{post_id}

    token_id
    access_token
```
**注意，这里也不返回200**

* 收藏成功返回
    ```json
    {
        "code": 4103,
        "data": "收藏成功"
    }
    ```

* 取消收藏成功返回
    ```json
    {
        "code": 4104,
        "data": "取消收藏成功"
    }
    ```
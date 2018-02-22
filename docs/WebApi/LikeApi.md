# 点赞
* 给文章点赞、给文章取消赞
* 给评论点赞、给评论取消赞

## 给文章点赞、给文章取消赞
```html
POST {{server}}/likes/posts/{post_id}
    
    token_id
    access_token
```
**注意，这里不会返回200**
* 点赞成功返回
    ```json
    {
        "code": 4101,
        "data": "点赞成功"
    }
    ```
* 取消赞成功返回
    ```json
    {
        "code": 4102,
        "data": "取消赞成功"
    }
    ```    

## 给评论点赞、给评论取消赞
```html
POST {{server}}/likes/comments/{comment_id}

    token_id
    access_token
```    
* 点赞成功
    ```json
    {
        "code": 4101,
        "data": "点赞成功"
    }
    ```
* 取消赞成功
    ```json
    {
        "code": 4102,
        "data": "取消赞成功"
    }
    ```


# 评论
* 发表评论（对文章）
* 回复评论（对评论）
* 删除评论
* 获取文章评论

## 发表评论（对文章）
```html
POST {{server}}/comments/posts/{post_id}

    token_id
    access_token
    body
```
* 成功返回评论信息
    ```json
    {
        "code": 200,
        "data": {
            "id": 1,
            "post_id": "1",
            "user_id": 1,
            "parent_id": 0,
            "level": 1,
            "body": "第一条评论",
            "created_at": "2018-02-21 01:38:42",
            "user": {
                "id": 1,
                "username": "cantjie",
                "nickname": "cantjie",
                "head_img": null,
                "signature": "个性签名",
                "email": "cantjie@163.com"
            },
            "popularity": {
                "favorite_count": 0,
                "comment_count": 0,
                "like_count": 0
            }
        }
    }
    ```
    
## 回复评论（对回复）
```html
POST {{server}}/comments/comments/{comment_id}

    token_id
    access_token
    body
```

* 成功返回评论信息
    ```json
    {
        "code": 200,
        "data": {
            "id": 4,
            "post_id": 1,
            "user_id": 1,
            "parent_id": "1",
            "level": 2,
            "body": "回复评论1的评论",
            "created_at": "2018-02-21 01:40:00",
            "user": {
                "id": 1,
                "username": "cantjie",
                "nickname": "cantjie",
                "head_img": null,
                "signature": "个性签名",
                "email": "cantjie@163.com"
            }
        }
    }
    ```

## 删除评论
```html
DELETE {{server}}/comments/{comment_id}

    token_id
    access_token
```

* 成功返回200
    ```json
    {
        "code": 200,
        "data": "删除评论成功"
    }
    ```
    
## 获取文章评论
```html
GET {{server}}/posts/{post_id}/comments
    
    cnt
    page
```

* 成功后返回评论列表
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 10,
                "post_id": 1,
                "user_id": 1,
                "parent_id": 0,
                "level": 1,
                "body": "第三条评论",
                "created_at": "2018-02-21 02:18:28",
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "favorite_count": 0,
                    "comment_count": 0,
                    "like_count": 0
                }
            },
            {
                "id": 9,
                "post_id": 1,
                "user_id": 1,
                "parent_id": 0,
                "level": 1,
                "body": "第四条评论",
                "created_at": "2018-02-21 02:15:31",
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "favorite_count": 0,
                    "comment_count": 0,
                    "like_count": 0
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/posts/1/comments?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/posts/1/comments",
        "per_page": 15,
        "prev_page_url": null,
        "to": 2
    }
    ```
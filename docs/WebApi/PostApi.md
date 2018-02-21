# 文章
* 发表文章
* 显示文章详情
* 更新文章
* 删除文章
* 获得某类别文章列表
* 获得首页文章列表

## 发表文章
```html
POST {{server}}/posts
    
    token_id
    access_token
    title 标题
    body 文章正文
    anonymous 是否匿名，0或1
    archive 类别id,应为二级类别的id
    description 描述，如果为空则等于标题
```
* 成功则返回文章内容
    ```json
    {
        "data": {
            "id": 1,
            "title": "第一篇文章标题",
            "description": "第一篇文章详情",
            "body": "第一篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:19:57",
            "updated_at": "2018-02-21 07:19:57",
            "archive": [
                {
                    "description": "干货",
                    "id": 1
                },
                {
                    "description": "黑科技",
                    "id": 2
                }
            ],
            "user": {
                "id": 1,
                "username": "cantjie",
                "nickname": "cantjie",
                "head_img": null,
                "signature": "个性签名",
                "email": "cantjie@163.com"
            },
            "popularity": {
                "like_count": 0,
                "comment_count": 0,
                "favorite_count": 0,
                "view_count": 0
            }
        },
        "code": 200
    }
    ```

* 失败返回原因及code
    ```json
    {
        "data": "类别不合理",
        "code": 2002
    }
    ```
 
## 显示文章详情
```html
GET {{server}}/posts/{post_id}
```
* 成功则返回文章内容
    ```json
    {
        "data": {
            "id": 1,
            "title": "第一篇文章标题",
            "description": "第一篇文章详情",
            "body": "第一篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:19:57",
            "updated_at": "2018-02-21 07:19:57",
            "archive": [
                {
                    "description": "干货",
                    "id": 1
                },
                {
                    "description": "黑科技",
                    "id": 2
                }
            ],
            "user": {
                "id": 1,
                "username": "cantjie",
                "nickname": "cantjie",
                "head_img": null,
                "signature": "个性签名",
                "email": "cantjie@163.com"
            },
            "popularity": {
                "like_count": 0,
                "comment_count": 0,
                "favorite_count": 0,
                "view_count": 1
            }
        },
        "code": 200
    }
    ```
    
## 更新文章
```html
PATCH {{server}}/posts/{post_id}
    
    所需参数与发表文章一致
```
* 成功后返回内容与发表文章一致

## 删除文章
```html
DELETE {{server}}/posts/{post_id}
    
    token_id
    access_token
```
* 成功返回200+空内容
    ```json
    {
        "data": null,
        "code": 200
    }
    ```

## 获得某类别文章列表

```html
GET {{server}}/archives/{archive_id}

    cnt
    page
```
此处的archive_id可为一级类别，也可为二级类别
* 成功返回文章列表
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 2,
                "title": "第二篇文章标题",
                "description": "第二文章描述",
                "anonymous": null,
                "views": null,
                "updated_at": "2018-02-21 07:22:09",
                "archive": [
                    {
                        "description": "干货",
                        "id": 1
                    },
                    {
                        "description": "教程",
                        "id": 3
                    }
                ],
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "like_count": 0,
                    "comment_count": 0,
                    "favorite_count": 0,
                    "view_count": 0
                }
            },
            {
                "id": 3,
                "title": "第三篇文章标题",
                "description": "第三文章描述",
                "anonymous": null,
                "views": null,
                "updated_at": "2018-02-21 07:22:21",
                "archive": [
                    {
                        "description": "干货",
                        "id": 1
                    },
                    {
                        "description": "教程",
                        "id": 3
                    }
                ],
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "like_count": 0,
                    "comment_count": 0,
                    "favorite_count": 0,
                    "view_count": 0
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/archives/3?page=1&cnt=2",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/archives/3",
        "per_page": "2",
        "prev_page_url": null,
        "to": 2
    }
    ```

## 获得首页文章列表
```html
GET {{server}}/posts/home

    cnt
    page
```
* 成功则返回文章列表
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 7,
                "title": "第7篇文章标题",
                "description": "第7文章描述",
                "anonymous": null,
                "views": null,
                "updated_at": "2018-02-21 07:23:06",
                "archive": [
                    {
                        "description": "干货",
                        "id": 1
                    },
                    {
                        "description": "教程",
                        "id": 3
                    }
                ],
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "like_count": 0,
                    "comment_count": 0,
                    "favorite_count": 0,
                    "view_count": 0
                }
            },
            {
                "id": 6,
                "title": "第6篇文章标题",
                "description": "第6文章描述",
                "anonymous": null,
                "views": null,
                "updated_at": "2018-02-21 07:22:51",
                "archive": [
                    {
                        "description": "干货",
                        "id": 1
                    },
                    {
                        "description": "教程",
                        "id": 3
                    }
                ],
                "user": {
                    "id": 1,
                    "username": "cantjie",
                    "nickname": "cantjie",
                    "head_img": null,
                    "signature": "个性签名",
                    "email": "cantjie@163.com"
                },
                "popularity": {
                    "like_count": 0,
                    "comment_count": 0,
                    "favorite_count": 0,
                    "view_count": 0
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/posts/home?page=1&cnt=2",
        "from": 1,
        "next_page_url": "http://workshop.test/api/posts/home?page=2&cnt=2",
        "path": "http://workshop.test/api/posts/home",
        "per_page": "2",
        "prev_page_url": null,
        "to": 2
    }
    ```
    

# 用户
* 登录
* 登出
* 注册新用户
* 获取当前用户信息
* 完善用户信息
* 注销用户
* 获取某用户信息
* 我的发布
* 个人中心-我收到的回复
* 个人中心-将收到的回复标为已读
* 个人中心-我收到的赞(不推荐)
    * 个人中心-我的文章收到的赞
    * 个人中心-我的评论收到的赞
* 个人中心-将收到的赞标为已读
* 个人中心-系统通知
* 个人中心-将系统通知标记为已读

## 登录
注意，这个可能不算是API，因为它会重定向到CAS登录界面。初次登陆会请求授权。
```html
GET {{webserver}}/login
```
* 登陆成功返回access_token、token_id、state。  
token_id和access_token需要储存至cookie,state表示是由哪个页面跳转至此，可供前端执行redirect命令。

    ```json
    {
        "data": {
            "access_token": "",
            "token_id": 1,
            "state": "http://workshop.test/login"
        },
        "code": 200
    }
    ```
* 登录失败时返回40X
    ```
    {
        "data": "Invalid state",
        "code": 401
    }
    ```

## 登出
```html
GET {{webserver}}/logout
```

```html
该功能尚不可用，无返回内容。
```

## 注册新用户
需要在登陆后调用该接口

```html
POST {{server}}/users

    token_id
    access_token
    nickname 必需，昵称
    signature 非必需，个性签名
    email 非必需
    email_access 非必需，邮箱是否公开，1表示公开，0表示不公开
    phone 非必需
    phone_access 非必需
    qq 非必需
    qq_access 非必需
```

* 成功返回用户信息，
    ```json
    {
        "data": {
            "id": 1,
            "username": "cantjie",
            "nickname": "Cantjie",
            "signature": "个性签名",
            "head_img": null,
            "email": "cantjie@163.com",
            "phone": null,
            "qq": null,
            "email_access": null,
            "phone_access": null,
            "qq_access": null
        },
        "code": 200
    }
    ```

* 失败返回如下
    ```json
    {
        "data": {
            "errors": "注册失败"
        },
        "code": 1004
    }
    ```
## 完善用户信息

```html
PATCH {{server}}/users
    
    所需参数与注册新用户一致
```  
* 成功返回用户信息

    ```json
    {
        "data": {
            "id": 1,
            "username": "cantjie",
            "nickname": "cantjie",
            "signature": "个性签名",
            "head_img": null,
            "email": "cantjie@163.com",
            "phone": null,
            "qq": "46472001",
            "email_access": "1",
            "phone_access": "0",
            "qq_access": "0"
        },
        "code": 200
    }
    ```
## 获取当前用户信息
```html
POST {{server}}/users/info

    token_id
    access_token
```    
* 成功返回用户信息
    ```json
    {
        "data": {
            "id": 1,
            "username": "cantjie",
            "nickname": "cantjie",
            "signature": "个性签名",
            "head_img": null,
            "email": "cantjie@163.com",
            "phone": null,
            "qq": "46472001",
            "email_access": 1,
            "phone_access": 0,
            "qq_access": 0
        },
        "code": 200
    }
    ```
## 注销用户
```html
DELETE {{server}}/users
    
    access_token
    token_id
```
* 成功返回200+空内容
    ```json
    {
        "data":{},
        "code":200  
    }
    ```

## 获取某用户信息
```html
GET {{server}}/users/{username}/info
```
* 成功则返回用户允许可见的信息
    ```json
    {
        "data": {
            "id": 1,
            "username": "cantjie",
            "nickname": "cantjie",
            "head_img": null,
            "signature": "个性签名",
            "email": "cantjie@163.com"
        },
        "code": 200
    }
    ```

## 个人中心——我的发布
```html
POST {{server}}/users/publish
    
    token_id
    access_token
    cnt(加在url后面即可)
    page(加在url后面即可)
```
* 成功则返回发布过的文章

    ```json
    {
    "code": 200,
    "data": [
        {
            "id": 7,
            "title": "第7篇文章标题",
            "description": "第7文章描述",
            "body": "第7篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:23:06",
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
            "body": "第6篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:22:51",
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
        },
        {
            "id": 5,
            "title": "第5篇文章标题",
            "description": "第5文章描述",
            "body": "第5篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:22:48",
            "updated_at": "2018-02-21 07:22:48",
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
            "id": 4,
            "title": "第4篇文章标题",
            "description": "第4文章描述",
            "body": "第4篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:22:35",
            "updated_at": "2018-02-21 07:22:35",
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
            "body": "第三篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:22:21",
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
        },
        {
            "id": 2,
            "title": "第二篇文章标题",
            "description": "第二文章描述",
            "body": "第二篇文章正文",
            "anonymous": null,
            "views": null,
            "created_at": "2018-02-21 07:22:09",
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
                "comment_count": 2,
                "favorite_count": 0,
                "view_count": 1
            }
        }
    ],
    "current_page": 1,
    "first_page_url": "http://workshop.test/api/users/publish?page=1&cnt=15",
    "from": 1,
    "next_page_url": null,
    "path": "http://workshop.test/api/users/publish",
    "per_page": 15,
    "prev_page_url": null,
    "to": 7
    }
    ```

## 个人中心-我收到的回复
```html
POST {{server}}/users/messages/comments
    
    token_id
    access_token
    cnt
    page
```

* 成功返回消息

    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 10,
                "has_read": 0,
                "created_at": "2018-02-21 02:18:28",
                "comment_id": 10,
                "comment": {
                    "id": 10,
                    "body": "第三条评论"
                }
            },
            {
                "id": 9,
                "has_read": 1,
                "created_at": "2018-02-21 02:15:31",
                "comment_id": 9,
                "comment": {
                    "id": 9,
                    "body": "第四条评论"
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/users/messages/comments?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/users/messages/comments",
        "per_page": 15,
        "prev_page_url": null,
        "to": 2
    }
    ```    
## 个人中心-将收到的回复标为已读
```html
PATCH {{server}}/users/messages/comments/{comment_message_id}

    token_id
    access_token
```
* 返回空信息+200
    ```json
    {
        "code": 200,
        "data": null
    }
    ```

## 个人中心-我收到的赞(不推荐)
该api无分页功能,建议用下面两个接口代替
```html
POST {{server}}/users/messages/likes
    
    token_id
    access_token
```
* 成功后返回收到的赞列表
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 3,
                "has_read": 1,
                "created_at": "2018-02-22 02:27:04",
                "like_type": "post",
                "post_like_id": 3
            },
            {
                "id": 2,
                "has_read": 0,
                "created_at": "2018-02-22 02:26:58",
                "like_type": "comment",
                "comment_like_id": 2
            }
        ]
    }
    ```

## 个人中心-我的文章收到的赞
```html
POST {{server}}/users/messages/likes/posts
    
    token_id
    access_token
    cnt
    page
```
* 成功后返回
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 3,
                "has_read": 1,
                "created_at": "2018-02-22 02:27:04",
                "like_type": "post",
                "post_like_id": 3,
                "post": {
                    "id": 1,
                    "title": "第一篇文章标题",
                    "description": "第一篇文章详情"
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/users/messages/likes/posts?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/users/messages/likes/posts",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1
    }
    ```
    
## 个人中心-我的评论收到的赞
```html
POST {{server}}/users/messages/likes/comments
    
    token_id
    access_token
    cnt
    page
```
* 成功后返回
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 2,
                "has_read": 0,
                "created_at": "2018-02-22 02:26:58",
                "like_type": "comment",
                "comment_like_id": 2,
                "comment": {
                    "id": 10,
                    "body": "第三条评论"
                }
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/users/messages/likes/comments?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/users/messages/likes/comments",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1
    }
    ```

## 个人中心-将收到的赞标记为已读
```html
PATCH {{server}}/users/messages/likes/{like_id}
    
    token_id
    access_token
    like_type 文章'post'或评论'comment'
```
* 成功返回
    ```json
    {
        "code": 200,
        "data": null
    }
    ```
    
## 个人中心-系统通知
**(该接口尚未测试)**
```html
POST {{server}}/users/messages/system

    token_id
    access_token
```

## 个人中心-将系统通知标记为已读
**(该接口尚未测试)**
```html
PATCH {{server}}/users/messages/system/{id}
```
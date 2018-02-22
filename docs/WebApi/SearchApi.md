# 搜索
* 搜索用户
* 搜索文章

## 搜索用户
```html
GET {{server}}/search/u
    
    wd 搜索关键词
    cnt
    page
```

* 成功返回用户信息
    ```json
    {
        "code": 200,
        "data": [
            {
                "id": 1,
                "username": "cantjie",
                "nickname": "cantjie",
                "head_img": "/storage/avatars/FFHPmbcZSrjhP2zOEGF56LZroLZmfpEgSvz6ko8H.jpeg",
                "signature": "个性签名",
                "email": "cantjie@163.com"
            }
        ],
        "current_page": 1,
        "first_page_url": "http://workshop.test/api/search/u?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/search/u",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1
    }
    ```
    
## 搜索文章
```html
GET {{server}}/search/p
    
    wd
    cnt
    page
```
* 成功返回文章信息
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
                    "head_img": "/storage/avatars/FFHPmbcZSrjhP2zOEGF56LZroLZmfpEgSvz6ko8H.jpeg",
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
        "first_page_url": "http://workshop.test/api/search/p?page=1&cnt=15",
        "from": 1,
        "next_page_url": null,
        "path": "http://workshop.test/api/search/p",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1
    }
    ```
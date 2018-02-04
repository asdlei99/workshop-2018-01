# WebApi


{{server}} 表示 http://host/api
## Post

发表文章  

返回
```JSON
{
    "data": {
        "id": 1,
        "title": "第一篇文章标题",
        "description": "第一篇文章描述",
        "body": "第一篇文章正文",
        "anonymous": null,
        "views": 84,
        "date": "2018 Feb 03 09:02:43",
        "user": {
            "data": {
                "id": 1,
                "username": "kohler.noelia",
                "nickname": "kohler.noelia",
                "head_img": null,
                "user_group": 3,
                "email": "bechtelar.jerad@example.com",
                "qq": null
            }
        }
    },
    "code": 200,
    "status": "OK"
}
```
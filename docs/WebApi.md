# WebApi

## 路径
    
    {{server}} 表示 http://servername/api
    {{webserver}} 表示 http://servername

## 数据返回格式

统一返回json格式数据
```
{
    "code": 200,
    "data": []
}
```
* data部分可以是{}或[]
* code=200表示成功，返回其他code请见 状态码.md

## 用户认证
* 在需要用户认证的地方，需要在http请求的body中加入'access_token'和'token_id'字段，这两个字段为用户登录时返回的字段，需前端保存至cookie中。  

## 分页
* 有分页功能的接口支持cnt和page参数,均为非必需参数
    1. cnt为每页多少项，默认为15
    2. page为第几项，默认为第一页
* cnt和page通过GET方法传递即可
    
## Popularity
* PostPopularity包括  
    1. like_count 点赞数
    2. comment_count   评论数
    3. favorite_count  收藏数
    4. view_count  浏览量 

* CommentPopularity包括
    1. like_count
    2. comment_count
    3. favorite_count
    
**注意，仅第一层评论有CommentPopularit**

## 尚未完成的功能
* 收藏功能
    1. 对评论的收藏
    2. 查看自己收藏的内容
* 搜索
* 头像
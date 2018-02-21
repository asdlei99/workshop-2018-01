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

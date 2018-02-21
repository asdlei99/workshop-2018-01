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
* 个人中心-我收到的赞
* 个人中心-将收到的赞标为已读

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
```
* 成功则返回发布过的文章

    ```json
    {
        "data": [],
        "code": 200
    }
    ```


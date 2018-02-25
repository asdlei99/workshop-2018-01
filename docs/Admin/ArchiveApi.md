# 类别
* 增加类别
* 修改类别
* 删除类别

## 增加类别
```html
POST {{server}}/admin/archives
    
    token_id
    access_token
    name
    parent 父类name
```

## 修改类别
```html
PATCH {{server}}/admin/archives/{archive_id}

    token_id
    access_token
    name
    parent
```

## 删除类别

```html
DELETE {{server}}/admin/archives/{archive_id}

    token_id
    access_token
```
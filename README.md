# LineLogin
```composer
composer require typerej/line-login dev-master --prefer-dist
```
# HOW TO USE

```php
use TyperEJ\LineLogin\Login;

$login = new Login(CHANNEL_ID,CHANNEL_SECRET);
$user = $login->requestToken($_GET('code'));
```

# GENERATE URL
```php
use TyperEJ\LineLogin\Login;

$login = new Login(CHANNEL_ID);
$url = $login->generateLoginUrl(options);
```
## REFERENCE
[https://developers.line.biz/en/docs/line-login/web/integrate-line-login/](https://developers.line.biz/en/docs/line-login/web/integrate-line-login/)

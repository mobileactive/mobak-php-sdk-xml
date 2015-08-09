PHP SDK для работы с XML API Mobile Active
==========================================

Для работы с XML API сервиса СМС-рассылок Mobile Active
вам необходимо иметь логин и пароль в указанном сервисе, 
для получения логина и пароля пройдите регистрацию на сайте [компании Мобильный Актив](http://mobak.ru)

Установка
-----------------

Для установки пакета используйте composer:

`composer require "mobileactive/mobak-php-xml-sdk" "1.0.*"`

или в файле composer.json в require-зависимости добавить:

```
{
  "require" : {
    "mobileactive/mobak-php-xml-sdk" : "1.0.*"
  }
}
```

Использование
----------------------------

Пример отправки СМС-сообщения:

```php
use mobak\Mobak;

$smsSender = new Mobak([
    'login' => 'YOUR_LOGIN',
    'password' => 'YOUR_PASSWORD',
])

$result = $smsSender->send([
    'message' => "Your message text",
    'sender' => "Info",
    'phone' => '79194839xxx'
]);

// получить результат в виде массива
var_dump($result->toArray());

```











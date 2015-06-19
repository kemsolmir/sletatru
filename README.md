Php клиент для api sletat.ru(слетать.ру)
==========================================

Php обертка для [api sletat.ru(слетать.ру)](http://sletat.ru/).


Установка
---------

**С помощью [Composer](https://getcomposer.org/doc/00-intro.md).**

Добавьте в ваш composer.json в раздел `require`:

```javascript
"require": {
    "marvin255/sletatru": "*"
}
```
```

**Обычная**

Скачайте библиотеку и распакуйте ее в свой проект. Убедитесь, что файл `Autoloader.php` подключен в вашем скрипте.

```php
require_once 'lib/Autoloader.php';
```


Использование
-------------

```php
//инициируем новый объект xml сервиса
$xml = new \sletatru\XmlGate(array(
	'login' => 'ваш логин для авторизации на сервисе',
	'password' => 'ваш пароль для авторизации на сервисе',
));
//получаем список городов вылета
$departCities = $xml->GetDepartCities();
```


Настройка
---------

При инициализации:

```php
$xml = new \sletatru\XmlGate(array(
	'login' => 'ваш логин для авторизации на сервисе',
	'password' => 'ваш пароль для авторизации на сервисе',
));
```

После инициализации:

```php
$xml->config(array(
	'login' => 'ваш логин для авторизации на сервисе',
	'password' => 'ваш пароль для авторизации на сервисе',
));
```

Опции
-----

* `wsdl` - ссылка на описание wsdl, по умолчанию `'http://module.sletat.ru/XmlGate.svc?wsdl'`;

* `login` - логин для авторизации на сервисе;

* `password` - пароль для авторизации на сервисе;

* `soapOptions` - настройки [SoapClient](http://php.net/manual/ru/soapclient.soapclient.php), по умолчанию `array()`;

* `catchExceptions` - если значение истинно, то все исключения будут перехвачены классом и внесены во внутренний массив ошибок, в противном случае исключения не будут обрабатываться, по умолчанию `true`;


Методы
------

Названия и сигнатуры методов совпадают с названиями и сигнатурами методов api. [Подробнее](http://static.sletat.ru/Files/Manual/XML_gate_Search.pdf).

Дополнительне методы
--------------------

* `array \sletatru\XmlGate::getErrors( void )` - возвращает массив ошибок, полученных во время запросов к сервису.

* `bool \sletatru\XmlGate::hasErrors( void )` - возвращает истину, если во время выполнения запроса были ошибки.

* `void \sletatru\XmlGate::clearErrors( void )` - очищает список ошибок.

* `array \sletatru\XmlGate::getHotelImageUrl( int $id, int $count[, int $width, int $height, int $method] )` - формирует ссылку на фотографию с порядковым номером $count отеля с идентификатором $id, указанной ширины и высоты.

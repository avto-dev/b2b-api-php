<p align="center">
  <img alt="logo" src="https://habrastorage.org/webt/59/df/45/59df45aa6c9cb971309988.png" wigth="70"  height="70" />
</p>

# PHP-клиент для работы с B2B API

![Packagist](https://img.shields.io/packagist/v/avto-dev/b2b-api-php.svg?style=flat-square&maxAge=30)
[![Build Status](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/build-status/master)
![StyleCI](https://styleci.io/repos/106674678/shield?style=flat-square&maxAge=30)
[![Code Coverage](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/?branch=master)
![GitHub issues](https://img.shields.io/github/issues/avto-dev/b2b-api-php.svg?style=flat-square&maxAge=30)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b52878bf-68dc-487c-adec-9ed67da79254/mini.png)](https://insight.sensiolabs.com/projects/b52878bf-68dc-487c-adec-9ed67da79254)

Данный пакет является реализацией клиента для работы с B2B API проекта "AvtoDev", значительно упрощающим работу с последним, предоставляя разработчику внятное API.

Все API-методы сопровождаются соответствующим `@phpdoc`.

## Установка

Для установки данного пакета выполните терминале следующую команду:

```shell
$ composer require avto-dev/b2b-api-php
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ composer test
```

#### Функциональное тестирование

Так же имеется возможность выполнения некоторых функциональных *(выполняющих реальные запросы к сервису B2B API)* тестов. Для этого после установки данного пакета выполните в терминале:

```shell
$ php ./vendor/bin/phpunit --group=feature
```

Предварительно изменив в [файле теста][feature_test_file] значение на:

```php
protected $is_test = false;
```

И создав файл `./tests/env.php` со следующим содержанием:

```php
<?php

return [
    'api' => [
        'versions' => [
            'v1' => [
                'base_uri' => 'https://some.host/b2b/api/v1',
            ],
        ],
    ],

    'use_api_version' => 'v1',

    'client' => [
        'domain'          => '%имя_вашего_домена%',
        'username'        => '%имя_вашего_пользователя%',
        'password'        => '%пароль_вашего_пользователя%',
        'report_type_uid' => '%uid_типа_отчета%',
    ],

    'is_test' => false,
];
```

Данные значения и будут использоваться для выполнения функциональных тестов (будут переданы в конструктор клиента, переопределив его значения, используемые "по умолчанию").

## Общая архитектура пакета

Данный пакет состоит из следующих основных компонентов:

 * [Клиент][client_v1], реализующий методы обращения к сервису B2B API;
 * [HTTP-клиент][http_client], реализующий методы осуществления запросов по протоколу `http` *(используется по умолчанию его реализация [`guzzle`][http_client_guzzle])*;
 * [Справочники][references], которые содержат некоторые основные значения *(такие как типы запросов идентификаторов и так далее)*;
 * [Генераторы и хэлперы токенов][tokens], при помощи которых производится генерация токенов *(авторизации на сервисе B2B - в частности)*;
 * [Классы типов данных][data_types], к которым автоматически приводятся возвращаемые от сервиса данные *(которые реализуют дополнительные методы-акцессоры)*, если это возможно. В противном случае всегда возвращается объект типа [`UnknownDataType`][UnknownDataType];

### Клиент

Клиент версии `v1` реализует работу с сервисом B2B API версии `v1.*`, и содержит следующие методы:

 * Проверка соединения:
> ```php
> $response = $client->dev()->ping();
> $result = $response->getValue('value'); // pong
> ```

 * Отладка формирования токена:
> ```php
> $response = $client->dev()->token($username, $password);
> $result = $response->getValue('header'); // Вернёт токен, строкой
> ```

 * Отладка формирования токена:
> ```php
> $token = \AvtoDev\B2BApi\Tokens\AuthToken::generate($username, $password, $domain);
> $response = $client->user()->info($token);
> /* @var \AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData $user_info */
> $user_info = $response->data()->first();
> $login = $user_info->getLogin(); // Вернёт логин, строкой
> ```

## Обратная связь и поддержка

Если вы обнаружите какие-либо проблемы при работе с данный клиентом, либо у вас появятся пожелания либо необходимость в каком-либо дополнительном методе то, пожалуйста, создайте соответствующий `issue` в данном репозитории.

[client_v1]:./src/Clients/v1/Client.php
[http_client]:./src/HttpClients/AbstractHttpClient.php
[http_client_guzzle]:./src/HttpClients/GuzzleHttpClient.php
[references]:./src/References
[tokens]:./src/Tokens
[UnknownDataType]:./src/Responses/DataTypes/UnknownDataType.php
[data_types]:./src/Responses/DataTypes
[feature_test_file]:./tests/SomeFeatureTestsTest.php
[getcomposer]:https://getcomposer.org/download/

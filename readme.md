<p align="center">
  <img alt="logo" src="https://habrastorage.org/webt/59/df/45/59df45aa6c9cb971309988.png" width="70"  height="70" />
</p>

# PHP-клиент для работы с B2B API

![Packagist](https://img.shields.io/packagist/v/avto-dev/b2b-api-php.svg?style=flat&maxAge=30)
[![Build Status](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/build-status/master)
![StyleCI](https://styleci.io/repos/106674678/shield?style=flat&maxAge=30)
[![Code Coverage](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/b2b-api-php/?branch=master)
![GitHub issues](https://img.shields.io/github/issues/avto-dev/b2b-api-php.svg?style=flat&maxAge=30)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b52878bf-68dc-487c-adec-9ed67da79254/mini.png)](https://insight.sensiolabs.com/projects/b52878bf-68dc-487c-adec-9ed67da79254)

Данный пакет является реализацией клиента для работы с сервисом B2B API, значительно упрощающим работу с последним, предоставляя разработчику внятное API.

Все методы API сопровождены соответствующим `@phpdoc`.

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/b2b-api-php
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].


## Компоненты

Данный пакет состоит из следующих компонентов:

Название | Описание
-------: | :-------
[Клиент][client_v1] | Реализует методы обращения к сервису B2B API
[HTTP-клиент][http_client] | Реализует методы осуществления запросов по протоколу `http` *(используется по умолчанию его реализация [`guzzle`][http_client_guzzle])*
[Справочники][references] | Содержат основные значения *(такие как типы запросов идентификаторов и так далее)*
[Генераторы токенов][tokens] | Производят генерацию токенов *(авторизации на сервисе B2B API - в частности)*
[Классы типов данных][data_types] | К которым автоматически приводятся возвращаемые от сервиса данные *(которые реализуют дополнительные методы-акцессоры)*, если это возможно. В противном случае всегда возвращается объект типа [`UnknownDataType`][UnknownDataType];

Каждый компонент в той или иной мере автономен, и может использоваться отдельно от других при необходимости.

## Жизненный цикл запроса

При создании инстанса клиента производится инициализация http-клиента и контейнеров-методов, каждый из которых отвечает за свою группу методов. Например, методы уровня пользователя вызываются с помощью `$client->user()->someMethodName()`, в то время как команды, предназначенные для разработчиков, но повторяющие в некоторой степени команды уровня пользователя -- `$client->dev()->user()->someMethodName()`. Таким образом, используя IDE для разработки, например, PHPStorm -- нажимая сочетание клавиш `cmd` + `пробел` после каждого метода вы увидите как список тех команд, что он реализует, так и "вложенные" контейнеры со своими командами.

При вызове любого метода API производится проверка -- включен ли режим тестирования (параметр конфигурации `is_test`), и если это так -- то реальный запрос **не** выполняется, а возвращается контент ответа из заранее подготовленных шаблонов, давая возможность произвести интеграцию с сервисом B2B API даже не имея учетной записи.

> Как перед осуществлением запроса, так и после него -- производится выполнение всех callback-функций, что были зарегистрированы для http-клиента. Подробнее об их использовании смотрите исходный код.

В случае, если режим тестирования не активен - то производится запрос к сервису B2B API. Если запрос завершился не корректным кодом, или в его процессе "что-то пошло не так" - будет брошено исключение. Поэтому, во избежание "падения" ваших приложений -- оборачивайте все вызовы клиента в блок `try { ... } catch (\Exception $e) { ... }`.

После получения ответа от B2B API -- вам возвращается объект типа `B2BResponse`, который хранит в себе как "сырой" контент ответа в виде массива, так и базовые методы-акцессоры к данным в нём. Более того - если ответ содержит в себе блоки данных *(контент отчета, статусная информация, прочее)* -- он пытается разобрать тип вернувшихся данных и дает вам доступ к ним с помощью своего метода `->data()`. При его вызове вам вернется объект типа `DataCollection` *(коллекция данных)*, который реализует удобные метода для взаимодействия с последними -- получить их количество, извлечь первый элемент, перебрать все с помощью callback-функции и прочие.

В зависимости от того, какой тип данных "прилетел" в ответе от B2B API -- создастся объект соответствующего типа, со своими методами-акцессорами. Например, если вернулся контент отчета - вам будут доступны удобные методы для получения статусов источников, имен источников, получения состояния стадии генерации отчета, отдельный метод для извлечения данных по "путям филдов" и так далее. Самое важное -- это проверить __тип__ объекта, что содержится в коллекции (его соответствие ожидаемому), и соответствии с ним использовать нужные методы-акцессоры.


## Настройка

Для того, чтоб указать клиенту какую версию B2B API использовать, по какому URI, и некоторые другие опции -- необходимо передать их в конструктор класса клиента в виде массива определенной структуры. Пример структуры конфигурации вы можете наблюдать ниже:

```php
<?php

$configuration = [
    'api' => [
        'versions' => [
            'v1' => [
                'base_uri' => 'https://some.host/b2b/api/v1',
            ],
        ],
    ],

    'use_api_version' => 'v1',

    // Блок 'client' используется *только* для функционального тестирования
    'client' => [
        'domain'          => '%имя_вашего_домена%',
        'username'        => '%имя_вашего_пользователя%',
        'password'        => '%пароль_вашего_пользователя%',
        'report_type_uid' => '%uid_типа_отчета%',
    ],

    'is_test' => false,
];
```

## Использование

В качестве отправной точки вы можете использовать следующий пример:

```php
<?php

use AvtoDev\B2BApi\Clients\v1\Client;

require __DIR__ . '/vendor/autoload.php';

$configuration = [/* настройки работы клиента */];
$client        = new Client($configuration);
$response      = $client->dev()->ping();

var_dump($response);
```

### Примеры

Ниже представлены некоторые примеры по работе с клиентом. Базовые операции -- проверить соединение с сервисом B2B API, заказать отчет, получить его контент:

```php
<?php

use AvtoDev\B2BApi\Tokens\AuthToken;
use AvtoDev\B2BApi\Clients\v1\Client;
use AvtoDev\B2BApi\References\QueryTypes;
use AvtoDev\B2BApi\Responses\DataTypes\User\BalanceData;
use AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportStatusData;

$configuration   = [/* настройки работы клиента */];
$client          = new Client($configuration);
$token           = AuthToken::generate('имя_пользователя', 'пароль', 'имя_домена');
$report_type_uid = 'uid_вашего_типа_отчета';

// Проверка соединения
$response = $client->dev()->ping();
$result   = $response->getValue('value'); // pong

// Получение информации о текущем пользователе
$user_info = $client->user()->info($token)
                    ->data()
                    ->first();
if ($user_info instanceof UserInfoData) {
    $result = $user_info->getDomainUid(); // Вернёт имя домена пользователя, строкой
}

// Проверка доступности квоты по UID-у типа отчета
$client->user()->balance($token, $report_type_uid)
               ->data()
               ->each(function (BalanceData $balance_info) {
                   if ($balance_info->isDailyBalance()) {
                       // Получаем значение суточной квоты
                       $quote_use = $balance_info->getQuoteUse();
                   }
               });

// Генерация нового отчета по ГРЗ 'A111AA177'
$report_status = $client->user()->report()->make($token, QueryTypes::QUERY_TYPE_GRZ, 'A111AA177', $report_type_uid)
                        ->data()
                        ->first();
if ($report_status instanceof ReportStatusData) {
    $report_uid = $user_info->getUid(); // Вернёт UID отчета
}

// Получение имеющегося отчета
$report = $client->user()->report()->get($token, $report_uid)
                 ->data()
                 ->first();
if ($report instanceof ReportData) {
    $sources      = $report->getSourcesNames(); // Массив имен источников
    $is_completed = $report->generationIsCompleted(); // true, если генерация отчета завершена
    $vin_number   = $report->getField('identifiers.vehicle.vin'); // Вернет VIN-номер ТС (при его наличии)
    $engine_kw    = $report->getField('tech_data.engine.power.kw'); // Вернет мощность двигателя в КвТ
    $content      = $report->getContent(); // Весь контент отчета, массивом
}
```

Методов извлечения данных значительно больше, чем указано в примерах выше. Если вы используете современную IDE для разработки -- все они будут вам "подсказаны" с подробным описанием.

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ composer test
```

#### Функциональное тестирование

Так же имеется возможность выполнения некоторых функциональных *(выполняющих реальные запросы к сервису B2B API)* тестов. Для этого выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/b2b-api-php.git
$ cd ./b2b-api-php
$ composer update --dev
```

Создайте файл `./tests/env.php` с параметрами, которые будут использоваться клиентом *(подробнее об этом смотрите в 
разделе "**настройка**")*, указав значение `is_test` равным `false`. После чего выполните в терминале:

```shell
$ php ./vendor/bin/phpunit --group=feature
```

## Обратная связь и поддержка

Если вы обнаружите какие-либо проблемы при работе с данным пакетом, либо у вас появятся пожелания или необходимость в каком-либо дополнительном методе то, пожалуйста, создайте соответствующий `issue` в данном репозитории.

-----

Лицензия: **MIT**

[client_v1]:./src/Clients/v1/Client.php
[http_client]:./src/HttpClients/AbstractHttpClient.php
[http_client_guzzle]:./src/HttpClients/GuzzleHttpClient.php
[references]:./src/References
[tokens]:./src/Tokens
[UnknownDataType]:./src/Responses/DataTypes/UnknownDataType.php
[data_types]:./src/Responses/DataTypes
[feature_test_file]:./tests/SomeFeatureTestsTest.php
[getcomposer]:https://getcomposer.org/download/

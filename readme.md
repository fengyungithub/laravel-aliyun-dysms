## Installation

1. add the following to your composer.json. Then run `composer update`:

```json
"pgroot/laravel-aliyun-dysms": "dev-master"
```

 or

```bash
composer require pgroot/laravel-aliyun-dysms
```

2. Open your `config/app.php` and add the following to the `providers` array:

```php
Mindertech\Dysms\MindertechDysmsServiceProvider::class
```


3. In the same `config/app.php` and add the following to the `aliases ` array: 

```php
'SendSms' => Mindertech\Dysms\Facades\SendSmsFacade::class,
'QuerySms' => Mindertech\Dysms\Facades\QuerySendDetailsFacade::class,
```

4. Run the command below to publish the package config file `config/dysms.php`:

```shell
php artisan vendor:publish --provider=Mindertech\Dysms\MindertechDysmsServiceProvider
```

## Configuration

Set the property values in the `config/dysms.php`.


## Usage


1. send sms

```php
try {
    $bizId = SendSms::to('SMS_123456', '18688886666', [
        'code' => mt_rand(1000, 9999)
    ]);
} catch(\Exception $e) {
    echo $e->getMessage();
}
```


2. query sms 

```php 
$config = [];
$page = 1;
$pageSize = 1;
$bizId = null;
$result = QuerySms::search('18688886666', '20180516', $config, $page, $pageSize, $bizId);
```

response

```json
{
	"page": 1,
	"pageSize": 1,
	"sendDate": "20180516",
	"result": [{
		"SendDate": "2018-05-16 16:04:53",
		"SendStatus": 3,
		"ReceiveDate": "2018-05-16 16:04:57",
		"ErrCode": "0",
		"TemplateCode": "SMS_123456",
		"Content": "",
		"PhoneNum": "18688886666"
	}],
	"total": 7
}
```

## Todo

* Send Batch
* SMS Queue
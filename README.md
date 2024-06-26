# Truemoney webhook gateway - PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/farzai/truemoney-webhook.svg?style=flat-square)](https://packagist.org/packages/farzai/truemoney-webhook)
[![Tests](https://img.shields.io/github/actions/workflow/status/parsilver/truemoney-webhook-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/parsilver/truemoney-webhook-php/actions/workflows/run-tests.yml)
[![codecov](https://codecov.io/gh/parsilver/truemoney-webhook-php/branch/main/graph/badge.svg)](https://codecov.io/gh/parsilver/truemoney-webhook-php)
[![Total Downloads](https://img.shields.io/packagist/dt/farzai/truemoney-webhook.svg?style=flat-square)](https://packagist.org/packages/farzai/truemoney-webhook)

ขอบคุณ [K'DearTanakorn](https://github.com/DearTanakorn)

เราได้ทำการ Fork มาจาก Repo: [DearTanakorn/truemoney-webhook-gateway](https://github.com/DearTanakorn/truemoney-webhook-gateway)

และให้ใช้งานร่วมกับ PHP Server ได้

---

### System Requirements
- PHP 8.0 or higher

### Setup
```bash
$ composer require farzai/truemoney-webhook
```

### Get started
```php
use Farzai\TruemoneyWebhook\Postman;

// New instance
$postman = new Postman([
   'secret' => 'your-secret-key',
]);

// Capture all data from incoming request
$data = $postman->capture();

// You can use $data to do anything you want.
// For example, you can get the data as Array
// @returned
// [
//    'event_type' => 'P2P',
//    'received_time' => '2022-01-31T13:02:23+0700',
//    'amount' => 100,
//    'sender_mobile' => '0988882222',
//    'message' => 'ค่าไอเทม',
//    'lat' => 1653538793,
// ]
$data->asArray();
```


Sometime, you may want to get some fields from incoming request.
You can use `$data->field_name` to get the value of the field.

For example:
```php
$data->event_type; // string
$data->amount; // int
$data->sender_mobile; // string
$data->message; // string
$data->lat; // int
```


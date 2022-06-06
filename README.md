# Truemoney webhook gateway - PHP

ขอบคุณ [K'DearTanakorn](https://github.com/DearTanakorn)

เราได้ทำการ Fork มาจาก Repo: [DearTanakorn/truemoney-webhook-gateway](https://github.com/DearTanakorn/truemoney-webhook-gateway)

และให้ใช้งานร่วมกับ PHP Server ได้

---

### System Requirements
```json
{
    "php": "^7.3||^8.0"
}
```

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


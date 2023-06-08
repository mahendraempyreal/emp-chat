# Laravel chat application using socket io

## 1. install package in your laravel app using below command
```bash
composer require mahendraempyreal/emp-chat
```

## 2. Add service provider inside config/app.php inside providers array
```bash
Mahendraempyreal\EmpChat\Providers\EmpchatProvider::class,
```

## 3. publish config file using below command
```bash
php artisan vendor:publish --tag=empchat-config
```

## 4. publish assets to public folder using below command
```bash
 php artisan vendor:publish --tag=empchat-assets
```

## 5. publish the migrations using below command & run migration
```bash
php artisan vendor:publish --tag=empchat-migrations
php artisan migrate
```

## 6. visit below url 
[http://localhost/laravel10-chat/public/empyreal-chat](http://localhost/laravel10-chat/public/empyreal-chat)
user need to logged in before use this url

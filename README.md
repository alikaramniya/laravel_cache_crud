## Laravel_crud_with_caching

#### علمیات crud رو با cache ها در لاراول با هم ترکیب کنیم نتیجش میشه سرعت بالا

#### بعد از clone کردن پروژه دستور دستور های پایین رو اجرا کنید که بدون مشگل براتون دیتابیسش ساخته بشه

```
cd laravel_cache_crud/
cp .env.example .env
composer install
php artisan key:generate
```

#### بعد از انجام شدن مراحل بالا برین داخل فایل .env وتنظیمات دیتابیس خودتون رو وارد کنید (username && password)

#### حالا دستورای پایین رو اجرا کنید

```
php artisan migrate
php artisan db:seed
```
##### برای دیدن بهتره نتیجه کار میتونید laravel-debugbar رو نصب کنید 
#### [laravel debugbar](https://github.com/barryvdh/laravel-debugbar)

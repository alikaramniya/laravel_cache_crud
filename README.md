## Laravel_crud_with_caching

#### علمیات crud رو با cache ها در لاراول با هم ترکیب کنیم نتیجش میشه سرعت بالا

#### بعد از clone کردن پروژه دستور دستور های پایین رو اجرا کنید که بدون مشگل براتون دیتابیسش ساخته بشه

```
cp .env.example .env
php artisan key:generate
composer install
```

#### بعد از انجام شدن مراحل بالا برین داخل فایل .env وتنظیمات دیتابیس خودتون رو وارد کنید (username && password)

#### حالا دستورای پایین رو اجرا کنید

```
php artisan migrate
php artisan db:seed
```

# CRUD Generator For PHP Super Framework
Ini adalah library tambahan untuk php superframework.
## Kebutuhan Sistem
1. SuperFramework 4.* [Install](https://github.com/crudbooster/superframework)
2. PHP 7.4 >=
## Instalasi
```bash 
$ composer require fherryfherry/crud-generator
```
Setelah melakukan instalasi, jalankan perintah berikut ini untuk menggenerasi halaman Admin Anda. 
```bash 
$ php super admin:init
```
## Perintah Yang Tersedia
| Command | Description |
| ------- | ----------- |
| admin:init | Untuk membuat halaman Admin, dijalankan pertama kali |
| make:crud {table} | Untuk membuat CRUD module |
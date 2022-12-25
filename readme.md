# CRUD Generator For PHP Super Framework
Ini adalah library tambahan untuk php superframework.
## Kebutuhan Sistem
1. SuperFramework 4.* [Install](https://github.com/crudbooster/superframework)
2. PHP 7.4 >=
## Instalasi
```bash 
composer require fherryfherry/crud-generator
```
Jika kamu mengalami kendalan `platform checking` jalankan perintah yang ini:
```bash 
composer require fherryfherry/crud-generator --ignore-platform-reqs
```

Setelah melakukan instalasi, jalankan perintah berikut ini untuk menggenerasi halaman Admin Anda. 
```bash 
php super admin:init
```
Untuk membuat CRUD pada suatu tabel, silahkan jalankan perintah berikut pada terminal:
```bash
php super make:crud {nama_tabel} --name="{nama_modul}"
```
Jangan lupa untuk mengganti `{nama_tabel}` dengan nama tabel yang Anda akan gunakan. Lalu ganti juga 
`{nama_modul}` untuk memberikan penamaan pada modul CRUD yang akan dibuat. Contohnya:
```bash
php super make:crud products --name="Master Produk"
```
## Perintah Yang Tersedia
| Command | Description |
| ------- | ----------- |
| admin:init | Untuk membuat halaman Admin, dijalankan pertama kali |
| make:crud {table} --name=[module name]| Untuk membuat CRUD module |
| make:crud {table} --name="Nama Modul" --tableDetail="{table_detail}"| Pada perintah ini --tableDetail digunakan apabila Kamu ingin membuat form master - detail (Ex. Order dan Items) |
| make:user --email=[email] --password=[pass] | Membuat user melalui cli |
## WYSIWYG
Plugin wysiwyg yang dipakai di CRUD Generator ini menggunakan library dari *Summernote*. Untuk menggunakan fitur 
upload image pada summernote, kamu harus mematikan CSRF Token pada path "admin/file-management". Silahkan tambahkan pada file `configs/App.php`
```php
"csrf_token_ignore"=> ["api/*","admin/file-management/*"],
```
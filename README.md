# API LMS UAS PEMROGRAMAN SISI SERVER
## Project Overview
Ini adalah API untuk sistem Learning Management System (LMS) yang digunakan untuk Ujian Akhir Semester (UAS) Pemrograman di sisi server. Proyek ini adalah bagian dari tugas pemrograman dan dibuat dengan dedikasi oleh tim developer dari HMTI UDINUS.

## Technology Stack
- Laravel - Framework PHP
- MySQL - Relational Database Management System

## Developer
Primavieri Rhesa Ardana 


## Prerequisite
Pada sistem operasi user telah terinstal `Docker Desktop` atau package `docker` & `docker-compose`

## Guide / Step-by-step
### 1. Clone Project
```shell
git clone https://github.com/LibraTechDev/API-LMS-SEDERHANA.git
```
Clonning project manajemen inventory ke directory yang sedang anda akses saat ini
### 2. Change Directory
```shell
cd container-inventory
```
Berpindah menuju directory / folder hasil dari project yang telah di clone
### 3. Install Project
#### 3.1. Linux & MacOS (UNIX)
```shell
./setup.sh
```
Melakukan setup installasi dari awal hingga akhir (container-frontend-backend), scripting yang membantu dengan menghindari serimonial setup ;)
#### 3.2. Windows 
Sayangnya scripting `setup.sh` tidak bisa berjalan kecuali user menggunakan wsl dengan mounting yang sesuai maka bisa apabila menggunakan cara reguler pada Windows sayangnya tidak bisa, user harus melakukan kegiatan seremonial setup :(
##### 3.2.1. Compose Up
```shell
docker-compose up -d --build
```
Bertujuan untuk inisialisasi awal seperti pembuatan `Dockerfile` dan `docker-compose.yml` menjadi suatu container
##### 3.2.2. Change Modifier
```shell
docker-compose exec app chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
```
Direcotry `/storage` dan `/bootstrap/cache` akan memiliki semua akses (Write, Read, Execute)
##### 3.2.3. NPM Install
```shell
docker exec laravel_app npm i
```
Menginstall segala dependency untuk frontend yang bersumber dari `package.json`
##### 3.2.4. NPM Build
```shell
docker exec laravel_app npm run build
```
Perintah yang menjalankan skrip `build` yang terdefinisi di file `package.json` dalam container `laravel_app`
##### 3.2.5. Composer Scope Install
```shell
docker exec laravel_app composer install
```
Menginstal dependensi PHP yang terdaftar di file `composer.json` dalam container `laravel_app`
##### 3.2.6. Duplicate .ENV File
```shell
docker exec laravel_app cp .env.example .env
```
Menyalin file `.env.example` menjadi file `.env` di dalam container `laravel_app`, yang digunakan untuk konfigurasi aplikasi
##### 3.2.7. Activate .ENV File
```shell
docker exec laravel_app php artisan key:generate
```
Menghasilkan dan mengatur kunci aplikasi baru untuk Laravel di dalam container `laravel_app`, yang digunakan untuk keamanan aplikasi
##### 3.2.8. Formatting .ENV File
Ubah file `.env` yang terletak di `/inventory-project`
```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=
```
Menjadi
```.env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=root
```
##### 3.2.9. Database
```shell
docker exec laravel_app php artisan migrate --seed
```
Migrasi database untuk memperbarui struktur tabel dan mengisi data awal (seeding) di dalam container `laravel_app`

#### Credential Login
```
Username : admin
Email    : admin@gmail.com
Password : sudo
```

## TROUBLESHOOT
Menemui masalah berupa tidak bisa menjalankan perintah
```
docker exec laravel_app php artisan migrate --seed
```

Hal ini terjadi karena  Kredensial database dalam konfigurasi container MySQL tidak benar. Mencoba membuat user root dengan MYSQL_USER yang tidak diizinkan - user root sudah dibuat secara otomatis dengan MYSQL_ROOT_PASSWORD, maka langkah yang perlu dilakukan adalah merubah `.env` nya menjadi berikut
```.env
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=root
```

lalu menambahkan config berikut di `docker-compose.yml` 
```yml
app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel_app
    restart: always
    working_dir: /var/www/html
    volumes:
      - ./inventory-project:/var/www/html
    depends_on:
      - db
    networks:
      - app-network

  db:
    image: mysql:8.0
    container_name: mysql_db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: inventory
    ports:
      - "3307:3306"
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:   # Adding healthcheck for monitoring (Optional)
      test: ["CMD", "mysqladmin", "ping", "-proot"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - app-network
```
Lalu jalankan ulang migrate seed nya , maka akan bisa menjalankannya

## Arsitektur Aplikasi
- **docker-compose.yml** - Konfigurasi yang digunakan oleh Docker Compose untuk mendefinisikan dan menjalankan multi-container Docker aplikasi, termasuk pengaturan layanan, jaringan, volume, dan penghubung antar container
- **Dockerfile** - File teks yang berisi serangkaian instruksi untuk membangun image Docker, termasuk pengaturan sistem, instalasi aplikasi, dan konfigurasi yang diperlukan
- **inventory-project** - Source code project aplikasi manajemen inventory 
- **nginx.conf** - File konfigurasi utama Nginx yang mengatur pengaturan server, rute trafik, dan interaksi dengan aplikasi 
- **setup.sh** - Script installasi setup untuk membuat container, frontend, backend, dan database

- **nginx.conf** - File konfigurasi utama Nginx yang mengatur pengaturan server, rute trafik, dan interaksi dengan aplikasi 
- **setup.sh** - Script installasi setup untuk membuat container, frontend, backend, dan database

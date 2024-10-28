<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Sistema de planilla

## Comandos importantes
Generar carpeta para el storage publico (Cargar imagenes de perfil):
```bash
php artisan storage:link 
```
## **Instalar PHP:**
https://windows.php.net/download#php-8.3
https://laragon.org/download/
https://getcomposer.org/download/
https://git-scm.com/
https://www.apachelounge.com/download/
https://www.postgresql.org/download/
## Editar php.ini   
extension=pgsql
extension=zip

## Figmas:
https://www.figma.com/design/qFPcwLzzVghpJ3TMciiqkT/Figmas-WG?node-id=0-286&t=X5yBuyiqNpqV11Zm-0

## Moonshine Larvavel DOCUMENTACION
https://moonshine-laravel.com/docs

## Guia para hacer esta mierda de ing:
https://www.youtube.com/watch?v=DJXFZq5g_FY&list=PLrAw40DbN0l0nqFzZNbDN3Olcn8za994p&index=4

## Relaciones de Bases de datos:
https://www.youtube.com/watch?v=5zWZzMMnslc
https://www.youtube.com/watch?v=IJYQf-l3_-w

**1. Instalar sistema**
## Clonar repository en C:\laragon\www 
## Github del sistema_wg
https://github.com/Luisby47/sistema.git

## Terminal de Laragon (Todos los comandos se hacen aqui)
`cd sistema`
`composer install`
`npm install`
> *Modificar el archivo env.example  (BD Y APP URL)*
> 
```bash
php artisan key:generate
```

## 2. Recuperar Respaldo BD ERP Y MOONSHINE
*Abrir terminal *
C:\Program Files\PostgreSQL\16\bin

```bash
php artisan migrate
``` 
``` bash
php artisan db:seed --class=SuperAdminSeeder
```
``` bash
php artisan storage:link`
```


## 3. Configurar URL 
> *Buscar hots en la siguiente carpeta*
%SystemRoot%\System32\drivers\etc
> *Añadir el siguiente dato*
127.0.0.1      sistema.local

## Añadir la siguiente config en apache de App de Laragon
<VirtualHost *:80>
ServerName sistema.local
DocumentRoot "C:/laragon/www/sistema/public"
<Directory "C:/laragon/www/sistema/public">
AllowOverride All
Require all granted
</Directory>
</VirtualHost>

## 4. Crear un nuevo recurso o modulo
Generar una migración y un modelo juntos:

```bash
php artisan make:model NombreModelo -m 
```

## Generar el resource:

```bash
php artisan moonshine:resource NombreModelo}
```

``` bash
php artisan migrate 
```
## Tips
Migracion en una tabla especifica 

```bash
php artisan make:migration nombre_de_la_migracion
```

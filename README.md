# appmailing

Proyecto Mailing

# Login Server


Instalación:
---

- Instalar PHP versión 7.2.34
- Instalar Laravel 5.6 
- Instalar SQL Server >= 14 
- NodeJS (v8.10.0) y npm (v6.10.2) 
- Instalar dependencias frontend: `npm install`
- Instalar dependencias backend: `composer install`



```

## Configuración PHP


```


Construir aplicación frontend con npm:
--
`cd mailing`

`npm install`

`npm run build`


Construir aplicación backend con PHP:
--
`cd mailing`

`php artisan serve`



Habilitar el puerto dinamico TCP/IP 1433 en la configuración de RED de SQL para usarlo en las variables de entorno:

Configuración de variables de entorno:
--
Archivo .env - se encuentra en la carpeta raiz del proyecto.

* `DB_CONNECTION` conexión al servidor (sqlsrv)
* `DB_HOST` nombre, ip o url del servidor
* `DB_PORT=1433` puerto del servidor.
* `DB_DATABASE=BDTKT` Nombre de base de datos
* `DB_USERNAME=` Usuario de base de datos
* `DB_PASSWORD` Clave del usuario de base de datos

---
Dany Navarro © 2025

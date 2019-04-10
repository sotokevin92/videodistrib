# VideoDistrib - reproducción remota y gestión de contenido
VideoDistrib es una sencilla aplicación basada en Laravel, con arquitectura cliente-servidor para control y reproducción de contenido en terminales cliente.

## Requerimientos
### Servidor
- PHP 7.1+
- MySQL
- Composer - en Windows es necesario descargar el instalador desde acá: <https://getcomposer.org/download/>
- Binarios de FFmpeg en el path del sistema - se pueden bajar desde acá: <https://www.ffmpeg.org/download.html>
- Configuración de PHP para permitir la subida de archivos grandes (de acuerdo al tamaño de los videos) - más información: <https://stackoverflow.com/questions/2184513/change-the-maximum-upload-file-size>


## Instalación
### Servidor
1. Clonar repo
1. Ejecutar `composer update`
1. Copiar `.env.base` a `.env` (se ignora en el repo) y personalizar la configuración de la base de datos.
1. Generar la clave en base64 para la app de Laravel - comando: `php artisan key:generate`.
1. Crear la base de datos vacía de acuerdo con la configuración del archivo `.env`
1. Ejecutar `php artisan migrate` y `php artisan db:seed` para generar las tablas y el usuario administrador.
1. Ejecutar el queue worker de Laravel: `php artisan queue:work`.
1. Servir app


## Notas
- El sistema admite la subida de archivos de cualquier tipo sin validación desde el servidor. El job de proceso falla y elimina el video.
- La API no está siguiendo una convención o estándar de especificación, y tampoco está documentada. Estoy aprendiendo a formalizar este punto.
- La vigencia de las listas es por fecha, mientras que la vigencia de los videos es por fecha y hora.
- El dashboard no está funcional, pero debería calcular la reproducción estimada actual de las pantallas y mostrarla usando los proxies. Dependiendo de qué tan intensivo sea en ancho de banda, estoy considerando agregar un segundo proxy en el proceso inicial, de 2 fps y ultra bajo bitrate, o un slideshow.

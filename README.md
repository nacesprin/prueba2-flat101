# Prueba
- Se ha utilizado `platform api` y docker para el desarrollo de la prueba.


## Pasos
- Descargar repo
- Versión usada de docker compose `Docker Compose version v2.35.1`
- Construir con `docker compose build --no-cache`
- Asegurarse que no existe en local el puerto 80 abierto
- Lanzar con `docker compose up -d`
- Entrar en https://localhost/api y aceptar la excepción de SSL para poder entrar.
- Lanzar tests unitarios con `docker compose exec php ./bin/phpunit --testdox`
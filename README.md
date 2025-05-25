# Prueba
- Se ha utilizado `platform api` headless y docker para el desarrollo de la prueba.
- Venía preparada para postgre pero se cambió para sqlite.


## Pasos
- Descargar repo
- Versión usada de docker compose `Docker Compose version v2.35.1`
- Construir con `docker compose build --no-cache` donde se crea la base de datos y genera la estructura básica.
- Asegurarse que no existe en local el puerto 80 abierto
- Lanzar con `docker compose up -d`
- Entrar en https://localhost/api y aceptar la excepción de SSL para poder entrar.
- Lanzar tests unitarios con `docker compose exec php ./bin/phpunit --testdox`


## Notas del candidato
No quisiera que se redujera a solamente esta prueba la oportunidad de demostrar mis conocimientos en desarrollo web, ya que me gustaría que también pudieran considerar otros de mis varios trabajos relacionados con otras variadas tecnologías y cuyos conocimientos he ido adquiriendo a lo largo de varios años, los cuales puedo aportar para la compañía.

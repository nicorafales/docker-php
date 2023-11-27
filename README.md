# docker-php

Just playing around with Docker + PHP from scratch. Following several tutorials and mixing previous examples of code.


## To Do's:

 1. Refresh DB doesn't run. The queries located on `MariaDbConnection::refresh` should execute with composer script (not recommended but handy).
 2. Fix, test and refactor basic logic for app. Several dumps are in place right now.
 3. Add propper tests
 4. Create entrypoint script, such as a makefile or .sh file to execute the commands.
 5. Configure Github actions


## How to make it run

`docker-compose -f docker-compose.dev.yml --env-file .env.local up --build -d`

and hit `http://localhost`

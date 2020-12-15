INSTALLATION WITH DOCKER
------------

### Prepare settings for DB connection 

Create file .env in project base directory. Copy the .env.example file to a local .env

Add your application configuration to a .env or use default congiguration for docker-containers.

### Build application with docker-compose 

Run docker-compose command:

~~~
docker-compose up --build
~~~

Connect to the docker-service (php) and run command to intall composer:

~~~
docker-compose exec php composer install
~~~

Connect to the docker-service (php) and run command to create DB tables:

~~~
docker-compose exec php php yii migrate --interactive=0
~~~

Now you should be able to access the application through the following URL:

~~~
http://localhost:9010/
~~~

Swagger for testing api endpoints through the following URL:

~~~
http://localhost:9010/documentation/
~~~

Adminer for management database through the following URL:

~~~
http://localhost:9020/
~~~

### Populating the database 

If you want to populate your database with test information, run command:

~~~
docker-compose exec php php yii fixture "*" --interactive=0
~~~

After testing you can clear database with command:

~~~
docker-compose exec php php yii fixture/unload "*" --interactive=0
~~~

To generate new test data, run command:

~~~
docker-compose exec php php yii fixture/generate-all --count=10 --interactive=0
~~~

INSTALLATION 
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer install
~~~

### File permissions

If composer don't run script postCreateProject:

~~~
chmod 0777 runtime
chmod 0777 web/assets
chmod 0755 yii
~~~

### Prepare settings for DB connection 

Create file .env in project base directory. Copy the .env.example file to a local .env

Add your application configuration(recommended) to a .env or use default congiguration for docker-containers.

### Populating the database 

If you want to populate your database with test information, run command:

~~~
php yii fixture "*" --interactive=0
~~~

After testing you can clear database with command:

~~~
php yii fixture/unload "*" --interactive=0
~~~

To generate new test data, run command:

~~~
php yii fixture/generate-all --count=10 --interactive=0
~~~

### Swagger

If you want to update swagger documentation run command:

~~~
./vendor/bin/openapi modules/api/controllers -o web/documentation/swagger.json
~~~

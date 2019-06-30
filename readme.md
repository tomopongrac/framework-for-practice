## Description
This is sample framwork created for practice. It follows course [The "No Framework" Framework](https://codecourse.com/courses/the-no-framework-framework).

## Requirements
You need to have docker on your computer.

## Installation
Clone repository
```
git clone https://github.com/tomopongrac/framework-for-practice.git
```
Open directory
```
cd framework-for-practice
```

Start the docker
```
docker-compose up -d --build
```

Find out your container id with command
```
docker ps
```

Connect to container bash
```
docker exec -it container_id bash
```

In your container create database
```
php vendor/bin/doctrine orm:schema-tool:create
```

Url for the app is http://localhost:8888/

## Features
User can register and login with remember me.
Forms have csrf protection.

## Packages
### Environment variables
For load environment variables i using package [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

### Container
For dependency injection container i using package [league/container](https://github.com/thephpleague/container)

### Routing
For routing i using [league/route](https://github.com/thephpleague/route)

### View rendering
I using twig

### ORM
I using Doctrine

### Form validation
I using [vlucas/valitron](https://github.com/vlucas/valitron)

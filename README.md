# mPHP
SmantU is a free open source php framework with machine learning opportunities.

## Getting started
Download the files from the main branch using

> git clone apronprof/smantu

run 

>composer install

you're up to work.


### File structure

**app**

This folder will contain controllers, models and middlewares you create.

**config**

This folder is used for giving the framework data about your app, database and middlewares and urls.

**core**

You must never touch this folder, there are core files located inside of it.

**db**

This folder is meant to be used for keeping migrations and file databases (sqlite3).

**public**

There are views and assets in this folder.

**vendor**

Composer folder.

<hr/>

### CLI

#### php console app:controller Name

creates a NameController.php file for a controller with base structure in app

> php console app:model Name

creates a Name.php file for a model with base structure in app

> php console db:migration Name

creates a Name.php file with base structure in db/migrations

> php console db:migrate

Activates all the migrations created

> php console db:rollback 

activates the rollback function of each migration

> php console ml:trainer Name

creates a new Name.php model trainer in ml/trainers

> php console ml:train Name

initiates the train for a trainer called Name.php

> php console app:middleware Name

creates a new Name.php middleware


### Routes

Routes are located in `config/urls.php`

By default the file with routes looks like this:

![image](https://i.ibb.co/Zcz9bHq/Screenshot-2019-01-28-14-40-37.png)

You can create new routes by using the methods `get()` and `post()` of the object `$router`. The method takes 2 parameters (route and controller).
route must look like this 'shop/items' without slashes at the start and the end of a string.

The second parameter have to look like 'name_of_the_controller@name_of_the_method'.

the third parameter is for middlewares 
[new \App\Middlewares\HttpMethodMiddleware(), ...]

In order to use parameters in url you need to use {} around the name of a parameter. (For example 'users/{id}'). The parameter will be in the array $parameters that will be given as the first parameter to your controller.

Use the method _404 to choose a controller and its method for handling unknown routes. It takes only one parameter that is controller.



## We're not done with this tutorial 

# Lazarus-db

## What is LazarusDB, 

Lazarus Db or Lazarus Database is Database Managerment Script built using php pdo, allowing support for Multiple Databases.

## How to install.

this project can be installed in two ways either simply download the file from github directly or install it using composer(Currently Unavaliable) 


    git clone https://github.com/mbamber1986/lazarus-db

    or

    composer require lazarusphp/lazarusdb


The Script Currently Consists of 1 class and two Traits which Control the script in different ways these include

* The Database Class
* Sql Management Trait
* Credentials Management Trait.


### The Database Class
The database class is a Static Singleton class used to create a persisitance Connection between your entire Class, this saves the need to constantly Call a new class instance everytime a Database Request is made 

In order to create a connection simpy use the Following commands


    <?php
    use LazarusPhp/LazarusDb;
    $config = "/path/to/config.php"
    Database::Connect($config);
    ?>

### Creating the Config file

    <?php
      //your db pdo driver tyoe 
      $type="mysql";
      //your db hostname
      $hostname="localhost"
      //your db username
      $username=""
      //your db passwoes
      $password=""
      //your database name
      $dbname=""
    ?>


<a href='Sqlmanager.md'>Click here</a> For Sql Manager Readme

Please Note this Script is currently a Work in progress project and is freely available to use without any Warrinty or responsibilty to any issues your website may come accross when using with your own scripts.
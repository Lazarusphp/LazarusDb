# Lazarus-db

Lazarus Db is a Database manager and Sql Manager class in one Script.
it is designed to work as the base to the lazarus Framework, However can be used standalone with some minor configuration

Please Note this Script is currently a Work in progress project and is freely available to use without any Warrinty or responsibilty to any issues your website may come accross when using with your own scripts.
this script can be used as a standalone script as well as part of the SnorkelWeb Framework which is again (wip)

# whats inludes
# Root Path Generator 
# Sql Manager
# Persistant Connection
# Installation
# Customisation.

# Installation

This features is currently not available (26/04/2024)

```
composer install lazarus-php/lazarus-db
```

# Usage

This script uses a persistant Database connection using the Database::Connect() method

```
 <?php
    use Lazarus/LazarusDb
    Database::Connect("Path/to/Config/FIle");
  ?>
```
S
Please Note : Please replace the Path to the config file with your own path

main features
 * sql generator
 * param binder

# creating a config file

```
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
```

the config structure as shown above must be used snd csnnot be changed as this is predifined witgub the class connection script and must match.

plesse change the values to match your database details.

# Generating Databse Requests.

As part of the Script as stated above it also serves as a Sql Manage, (not an Orm)

in order to Generte a prepared statment simply enter the Snippet below, Please make sure you have estabblished your datbaase Connection.

```
<?php

use Lazarus-php/lazarusDb;

Database::GenerateQuery($query);
?>
```
In order for this script to work please Add your Sql Statment or attacch it to a Variable with the same Name $query. 
Please see Fetch and FetchALL For information on Retreiving the Data.

# LazarusDb : Query Builder

## What is lazarus db QueryBuilder
 QueryBuilder is an object orientated Sql Query Builder, the sql statement is built up in fragments and merged and binded using named parameters.


## Downloading and installingf

```php
composer require lazarusphp/lazarusdb
```

 ## Instantiation

Setting up and using the querybuilder can be done with three different approaches, each has the same outcome.

* Extending the Querybuilder
    * Extending the query builder from another class ie : users

    * Using this method the table name does not need to be passed as the Querybuilder obtains it dynamicly.


```php

namespace App\System\Http\Model;
use Lazarusphp\LazarusDb\QueryBuilder;
class Users Extends QueryBuilder
{

}

$users = new Users();
$users = $users->select()->save();
dd($users->fetchAll());
```

* Directly Calling the QueryBuilder
    * Directly Calling the QueryBuilder class.

    * this approach requires a table name to be passed.


```php
$users = new QueryBuilder("users");
$users = $users->select()->save();
dd($users->fetchAll());
```

* Statically calling QueryBuilder
    * Statically calling querybuilder class.

    * table must also be applied statically.

```php

 $users = QueryBuilder::table("users")->select()->save();
 dd($users->fetchAll());

```

dd() does a var_dump of all data from query.


 <pre>
 array(3) {
  [0]=>
  object(stdClass)#7 (4) {
    ["username"]=>
    string(4) "mike"
    ["created"]=>
    string(19) "2025-04-25 08:58:35"
    ["updated"]=>
    string(19) "2025-04-25 08:58:35"
  }
  [1]=>
  object(stdClass)#8 (4) {
    ["username"]=>
    string(6) "martin"
    ["created"]=>
    string(19) "2025-04-25 22:04:06"
    ["updated"]=>
    string(19) "2025-04-25 22:04:06"
  }
  [2]=>
  object(stdClass)#4 (4) {
    ["username"]=>
    string(5) "james"
    ["created"]=>
    string(19) "2025-04-25 22:06:39"
    ["updated"]=>
    string(19) "2025-04-25 22:06:39"
  }
}
 </pre>

## Using a different database name.
the QueryBuilder can swap out the database from another  by using the onConnection() method

```php
// Statically : note that the table can be called as an object when using onConnection.

$users = QueryBuilder::onConnection("dbname2")->table("users")->select()->save();


// Object orientated : table name is needed when calling the querybuilder directly.

$users = new Users();
$users->onConnection("dbname2")->select()->save();


dd($users->fetchAll());
```

## Selecting Specific Data
the QueryBuilder select method allows the ability to target specific data.
leaving the select method empty will display everything.

```php
$users = new Users();
$user = $users->select("username")->save();
echo $user->fetch()->username;
```
find out more about restricting data by clicking [Here](Select.md) 




# The Lazarusphp  Database Manager

## What is Lazarus DB.
 Lazarus DB is an all in one Abstraction Database Management class and Query Builder.

## Requirments
* A config file, an array of data or an .env File
* a Server with support for php
* Knowlege of php and the use of composer.

## How to install?
it is recommended to use Composer to install 
this package, using composer will download the required files and all dependencies.
```php
composer require lazarusphp/databasemanage;
```

# How to instantiate
in order to instantiate a Connection between any website/system and its database the database connection must be activated.

```php
Connection::activate();
```
**Persistant connection and non Persistant connection**
as part of the new v1.1.1 update, the database class now supports both persistant and non persistant connection

in order to disable a persistant connection simply add false into the activate parameter like so.

```php
Connection::activate(false);
```
**Env and php config files**

new to version V1.1.1, .env format is supported by default, if a php config file is the prefferred option this can be accomplished by simply adding the file method above activate will override the env format.

```php
Connection::file("/path/of/Config.php");
Connection::activate();
```

# Query Builder
 
as part of a v1.1.1 the previous methods one all and count have been removed in favour for merging the QueryBuilder Repository with LazarusDb Previously DataBaseManager.


**Instantiation**
in order to access QueryManager this can be obtained using three different approaches 

1. new Instantiation
2. QueryBuilder::table();
3. Extending it from another class.

```php
$users = new QueryBuilder("users");
```
```php
QueryBuilder()->table("users");
```
Extending works a little different to the above examples and does not require a table to be added as the method will grab the table name from the called class, this method is more ideal for MVC frameworks.

```php
//Users Model
class Users extends QueryBuilder()
{

}

// Users Controller
public function index(Users $users)
{
    $users->select();
}
```

**Reading Data from the Database**

```php
QueryBuilder::table("users")->select()->save();
```

**Adding Data into the Database**#

Adding Data Requires the use of Magic methods __set and __get
```php
$users = QueryBuilder::table("users");
$users->username = "Jack";
$users->password = password_hash("test1245",PASSWORD_DEFAULT);
$users->insert();
```

**Updating Records in the Database**

Using update requires both the use of magic methods and the where clause, by not using where the risk of updating all records with the same value is high.

```php
$users = QueryBuilder::table("users");
$users->username = "jason";
$users->update()->where("id",1)->save();
```

**Deleting Records from the Database**
In order to Delete Records from the database like the above example a where clause must be added to prevent the risk of mass deletion.

```php
QueryBuilder::table("users")->delete()->where("id",1)->save();
```

**Saving Data**
In order to save data and send it the database the Save method must be used.

within the querybuilder there are 4 methods of saving these include save() get() countRows and first()

1. save(): this method gives more freedom to the code allowing the use of fetch, fetchAll and rowCount, this method does not require reinstantiation.

2. get(): this method is used to pull multiple records and is recommened to be used with a foreach Loop like so
    ```php
    $users = QueryBuilder::table("users")->select()->where("id",1,">")->first();

    foreach($users as $user)
    {
        echo $user->username . "<br>";
    }
    ```

3. first(): as the method name states this will pull the data from the first record chosen from the sql statement and should be used with a where clause

    ```php
    $users = QueryBuilder::table("users")->select()->where("id",1)->first();
    echo $users->username;
    ```
 4. countRows(): this method like the first and get is used to simply get how many rows have been found

    ```php
    $users = QueryBuilder::table("users")->select()->where("id",1);
    echo $users->CountRows();
    ```

[click here](./src/QueryBuilder/QueryBuilder.md) for more indepth options of the QueryBuilder controllers and clauses.





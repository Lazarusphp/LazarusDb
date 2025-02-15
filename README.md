# The Lazarusphp  Database Manager

## What is the Database Manager.
The Database Manager is an Abstract Pdo Database class designed to interact between the website and the server.

## Requirments
* A config file, an array of data or an .env File
* a Server with support for php
* Knowlege of php and the use of composer.

## How to install?
it is recommended to use Composer to install 
this package, using composer will download the required files and all dependencies.
```php
composer require lazarusphp/databasemanager
```

# How to instantiate
in order to use this database Script it must first be instantiated, Connection::instantiate() is used to pass the Credentials to the script ready to use with the Database class.

The Connection Class only needs to be used once at the beginning of the script.

The Connection class requires one of three  methods of input for its parameters : a file,an array or env variables if used with an .env parser.


> Leaving the parameter blank will trigger an error.

### Example 1 : Env Parser

when using an env parser such as DotEnv, The database manager uses predefinded keywords which are passed to as $_ENV variable these must be used and are as follows.

* type
    * the pdo Driver type.
* hostname
    * hostname of the database server
* username
    * username of the server
* password
    * password for the server
* dbname
    * database name for the server.

```php
use Lazarusphp/DatabaseManager/Connection.php
Connection::instantiate("env");
```

> do not make credentials public as this can expose your server.

### Example 2 : The Config File.

the second method of passing data to the database class is by using a php based Config file.

the variables in a config file must be set as follows as these keywords are predefined by the Databasemanager

```php
// php Config file.
<?Php 
$type="";
$hostname="";
$username="";
$password="";
$dbname="";
>
```

```php
use Lazarusphp/DatabaseManager/Connection.php
Connection::instantiate("/path/to/config/file.php");
```
### Example 3 : as an array value.

the third method of passing data to the database class is using an array key pair.

this array data is predefined by the class and must match the following keys.`

```php
use Lazarusphp/DatabaseManager/Connection.php
$credentials = [
"type"=>"mysql",
"hostname"=>"localhost",
"username"=>"dbuser",
"password"=>"dbpassword",
"dbname"=>"dbname"];

Connection::instantiate($credentials);
```




## Using the database Queries.

the database class can make a connection to the database in a restricted mode and non-restricted this is done with how the methods are called.

All restricted methods must use the $this->sql($sql,$params) format in order to pass data.

### Example User class

The user class will be used to display record using the One and All method along with RowCount;
> due to the database being abstract it cannot be called and must be extended
```php
class Users extends Database
{

    public function getUsers()
    {

    }

}
```

#### Fetching data with the One Method

If the need to pull one record from the database is required the One() method can be used to accomplish this and can be done as follows.

```php
class Users extends Database
{

    public function getUsers()
    {
        $this->sql("SELECT * FROM  users WHERE id=:id",[":id"=>1]);
        return $this->one();
    }
}

// instantiate the class and display User with the id of 1
$users = new Users();
$user = $users->getUsers();
$user->id;
```


### Fetching date using the All Method
```php
class Users extends Database
{
    public function getUsers()
    {
       $query = $this->sql("SELECT * FROM users");
        return $this->All();
    }

}

    $users = new Users();
    foreach($users->getUsers() as $user)
    {
        echo $user->username . "<br>";
    }
```

### Counting Rows;



```php
class Users extends Database
{
    public function countUsers($id)
    {
       $query = $this->sql("SELECT * FROM users WHERE id >= :$id");
        return $this->RowCount();
    }

}

    $users = new Users();
    $count = $users->countUsers();
    echo $count;

    if($count > 1)
    {
        echo "More than one user";
    }
```

These three restricted methods can be used together ie One() and RowCount() or All and RowCount(), however by directly calling GenerateQuery the request becomes less restricted and gives more control like in the example below

* restricted methods cannot chain any further commands past there initial fetch() request

```php
class Users extends Database
{

    public function getUsersbyId($id)
    {
        return  GenerateQuery("SELECT * FROM  users WHERE id=:id",[":id"=>$id]);
    }

    public function getAll()
    {
        return  GenerateQuery("SELECT * FROM  users");
    }

      public function deleteUserById($id)
    {
        return GenerateQuery("DELETE FROM  users WHERE id=:id",[":id"=>$id]);
    }

// find the user by id, make sure only one row exists and display username
    $users = new Users();
    $user = $users->getUsers(1);
    // Row Found 
    if($user->rowCount() == 1)
    {
        $user = $user->fetch();
        // Display username
        echo $user->username;
    }

    // Delete a user with id 2;
    $users->deleteUserById(2);#

    // Get All users  and display username

    $users = new Users();

    foreach($users->fetchAll() as $users)
    {
        echo $user->username . "<br>";
    }
}
```

> the database Manager has been tested on php version 8.3 and 8.4 and currently supported on these versions
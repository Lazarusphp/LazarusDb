# <p align="center"> SqlManager</p>

As Part of the Database Manager Script, it also included a Work in progress SqlManager Trait, this is Explicitly tied to the Database Class, using it standalone Would Possibly Break it with understanding how it works.

```
  public static function GenerateQuery(string $sql,$array=null)
    {
            self::$stmt = self::$connection->prepare($sql);
            // Using ParamBindiner
            self::parambinder();
            self::$stmt->execute();
            return self::$stmt;
            // Check if parameters array is not empty
    }
```

the Base of this trait is the Snippet Above, the snippet takes in the Sql Statement and then Prepares and sanatisies with BindingValues using "Names Values (:id)"; 

## Generating the Sql
to generate the sql simply do the following line of code.

Please note the Database Connection Must be instantiated in order for this to work.

```
use LazarusPhp\LazarusDb;

$query = Database::GenerateSql($sql);
```
Once Called this class can then Chainload other method like.

* Fetch();
* FetchAll()
* RowCount();

Please be aware Lastid can be used without chainloading but must be used after an insert Query.

### The WithParams() Method

the Withparams Method is a static method used to tie named sql values to the param Binder and can be done like so 

```
use LazarusPhp\LazarusDb;

Database::WithParams(":name","Jack");
$sql = "select * from users where name = :name";
$query = Database::GenerateSql($sql);
```
Once the data is passed along the Sql Command The Generate it will bind the Data and value and execute the Statement Safley.

if you do not wish to use the WithParams() method you can also bind the Values using arrays at the end of the GenerateSql() method like so

```
$sql = "select * from users where name = :name";
$query = Database::GenerateSql($sql,[":name"=>"Jack"]);
```

The Data passed as an array will pass directly to the GenerateSql() method and will not use the WithParam() method

Please note although not advised, you can also Pass a direct sql statment without any Binding

# LazarusDb SchemaBuilder
* Version 1.0


## What is the Scheme QueryBuilder

LazarusDb Scheme builder is an object based Database Creation class.

## Instantiation
in order to use the schema builder class an instantiation must be first made, the class offers both both an object based and static based instantiation process.

the instantiation process requires a table name to be passed in the form of a parameter this can be in either a string for singular tables or an array for multiple.

**Note :** Not all methods accept multiple tables.

```php
$schema = new Schema("users");
```

```php
Schema::table("users");
```

### current supported options


# Creation Tools
the Schema class comes with Creation tools, tools that allow the creation of a table along with its columns.

## create Method;

```php
Schema::table("users")->create(function($table)
{
    $table->integer("id")->ai()->store();
    $table->string("username","100")->unsigned()->store();
    $table->primaryKey("id")->store();
    $tanble->build();
})
```
 
 **Supported colum datatypes**

* integer
* bigint;
* string
* text 
* largetext

# Modifier Methods.

## rename Method
```php
schema::table("users2")->rename("users2");
```
## Alter method
The Alter Method is used to change or rename the value of a colum and its datatype.

**Supported datatypes**
* integer
* bigint;
* string
* text 
* largetext

# Deletion tools
## Delete method
The Delete method checks if the table exists and executes a DROP table sql Query.

```php
$schema = new Schema("users");
$schema->delete();
// or
Schema::table("users")->delete():

```

## Empty Method
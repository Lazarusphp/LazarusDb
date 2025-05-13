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

The Schema Builder has the ability to create alter, rename and drop tables as well as set and create indexes


## Current supported data types

* tinyint()
* int()
* bigint()
* varchar()
* text()
* mediumText()
* longText()
* date()
* dateTime


## supported indexes

* primary
* index
* unique index

## other featueres

* ai() AutoIncrement
* default()
* now()
* timestamp();

## foreign Key Support
 
 * constraint()
 * foreignKey()
 * onUpdate()
 * onDelete()

## Example coode structure 

the following example will demonstrate how to create a new table called users.

## create Method;

```php
Schema::table("users")->create(function($table)
{
    $table->int("id")->ai()->store();
    $table->varchar("username",100)->store();
    $table->varchar("password")->store()
    $table->priimaryKey("id")->store();
    $table->build();
})
```

## Example code for Dropping a table

if needed  the ability to drop (delete) a table from the database is possible by utilising the  the  drop method and is done like so.

```php

Schema::table("users")->drop();

```

## Example code for Renaming a table

in order to rename a table the called table name must be called within the table method and the new table name must be called in the  rename table as shown below.

```php

Schema::table("users")->rename("members");

```

## Creating indexes

```php

Schema::table("users")->index("username");

// can also bve accomplishes like so

Schema::table("users")->create(function($table)
{
    $table->int("id")->ai()->store();
    $table->varchar("username",100)->store();
    $table->varchar("password")->store()
    $table->index("username")->store();
    $table->priimaryKey("id")->store();
    $table->build();
})
```

## Unique Indexes


```php

Schema::table("users")->unique("username");

// can also bve accomplishes like so

Schema::table("users")->create(function($table)
{
    $table->int("id")->ai()->store();
    $table->varchar("username",100)->store();
    $table->varchar("password")->store()
    $table->unique("username")->store();
    $table->priimaryKey("id")->store();
    $table->build();
})

```

## Checking a table exists

```php

if(Schema::table("users")->hasTable())
{
    echo "the table has been found";
}
else
{
    echo "Table not found";
}

```
## Select Readme

[<< Back to readme](Readme.md)

## Selecting All Columns;
By leaving select `blank` the select method will tell the statement to select all columns.

```php
$users = new Users();
$users->select()->save();
```

## Selecting Specific Columns
Choosing Specific data is possible by passing each value in a set of `double` or `single` quotes. if a non specified Column is called an error will be thrown.

```php
$users = new Users();
$users->select("username","email")->save();
```

## Adding an alias to the called table.
it is possible to apply an optional alias to the called table by using the `as()` method which can then be used with other built in methods such as [Where](Where.md) when using [Joins](Joins.md)

```php
$users = new Users();
$users->select()->as("u")->save();
```

## Pulling and merging data from two tables or more tables.
`union()` us used to pull data from another table.
```php
$users = new Users();
$users->select()->union("users2")->save();
```

use `unionAll()` if the required data can include Duplicate Records.


```php
$users = new Users();
$users->select()->union("users2")->save();
```
# Using Joins

## Contents 
1. [Join](#join)  
2. [Inner Join](#innerjoin)  
3. [Outer Join](#outer-join)  
4. [Full Joins](#full-joins)  
5. [Left Join](#left-join)  
6. [Right Join](#right-join)  
7. [Cross Join](#cross-join)  


## Join

```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->join("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## InnerJoin

Like  join Innerjoin bar namesake does the same as  join.

```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->innerJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users INNER JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## Outer Join

```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->outerJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users OUTER JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## full joins


```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->fullJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users FULL JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## Left Join


```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->leftJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users LEFT JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## Right Join


```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->rightJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users RIGHT JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```

## Cross Join


```php
// instantiate if not extending from a model.
$qb = new QueryBuilder("users");
$qb = select("users.username","posts.title")->crossJoin("posts","posts.uid","users.id")->where("users.username","mike")->get();
```
the following would output 
```sql
SELECT users.username, posts.title FROM users CROSS JOIN posts ON posts.uid = users.id WHERE users.username = "mike";
```
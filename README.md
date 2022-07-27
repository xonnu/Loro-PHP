# 🐦 Parrot PHP - MySQL and PHP functions
> Documentation is working in progress - July 27, 2022

### What is Parrot PHP?

It's a **simple procedural PHP functions** that makes MySQL and PHP language easier for newbie developers. It can be used in `school projects` or in a `simple web application system`.
<br /><br />
## How it works
Download the latest release file and paste it into your project, and use `include` or `require_once` to import *Parrot PHP functions* to your project.
<br /><br />
## Functions and Usage
> functionName(props) : return value

`createQuery() : string` - Create an SQL insert statement
```php
createQuery(database_conection, table_name, [
  column_name: value
]);
```
`column_name` needs to be exactly what's on your table.

Examples:
```php
createQuery(database_conection, table_name, [
  email: 'heychrono@sample.com',
  name: 'Chrono'
]);

```
```php
createQuery(database_conection, table_name, [
  email: $_POST['email'],
  name: $_POST['name']
  password: password_hash($_POST['password'], PASSWORD_DEFAULT)
]);

```
---

`isExists() : boolean` - Checks if the data already exists in the database.
```php
isExists(database_connection, table_name, column_name, user_data)
```
Example: 

```php
if(!isExists(database_connection, table_name, 'name', 'Chrono')):
  echo "This name is new to our database.";
else:
  echo "This name is already existing in the database.";
endif;
```
```php
if(!isExists(database_connection, table_name, 'email', $_POST['email'])):
  echo "This email is new!";
else:
  echo "This email is already on the database";
endif;
```
---
`sanitize() : string` - Clean up the data to avoid injecting vulnerabilities*(e.g. XSS, HTML injection)*. It disabled HTML tags in data.
```php
sanitize(database_connection, data);
```
Examples:
```php
sanitize(database_connection, '<h1>HuckerMan</h1>');
```
> Output: * Huckerman *
```php
sanitize(database_connection, $_POST['name']);
```

# php-env
Simple Environment package

## Requirements

On root folder, create a .env file with information you need:

```
[database]
driver = mysql
server = localhost
port   = 3306
dbname = testdb
user   = root
password = 

[some_section]
key1    = value1
key2 = "value3"

[other_section]
key1    = value2
key2 = "value6"
```

## Usage

### Retrieving a section

```php
use LCloss\Env\Environment;

$path_from = '.' . DIRECTORY_SEPARATOR;

$env = Environment::getInstance('.env', $path_from);
$database = $env->database;

// Or...
$datbase = $env->getSection('database');
```

### Retrieving a property

```php
use LCloss\Env\Environment;

$env = Environment::getInstance();  // Will retrieve .env file from current path
$key1 = $env->key1; // Will retrive first property with this 'key1': value1

// Or...
$key1 = $env->getKey('other_section', 'key1');  // Retrieve: value2
```


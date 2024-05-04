# How to use?
## 1 - Clone project to your local computer
```bash
git clone https://github.com/Arshia-Moharrary/aceBlog.git
```
## 2 - Install php and a database (for example mysql)
```bash
sudo apt install php
sudo apt install mysql
```
## 3 - Config database connection
Open aceBlog > includes > dbinfo.php and edit these variables
```php
// Database info
$rdbms = "mysql"; // Your rdbms like: mysql, microsoft sql, sql server and ...
$host = "localhost"; // Your host ip (localhost for your local computer)
$database = "aceBlog"; // Your database name
$serverUsername = "root"; // Put your database username
$serverPassword = ""; // Put your database password
```
## 4 - Run a virtual server (You can change port)
Open aceBlog directory and run this command
```bash
php -S localhost:9000
```
## 5 - Config database (create db and tables)
Open this address in the browser:<br>
[http://localhost:9000/dbc.php](http://localhost:9000/dbc.php)
and click on config button
## 6 - Create first admin account
Open this address in the browser and create a admin account:<br>
[http://localhost:9000/admin.php](http://localhost:9000/admin.php)
## 7 - Delete some file for security reason
Delete dbc.php and admin.php files so that normal users cannot access them

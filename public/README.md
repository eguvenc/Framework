
## Host configuration for single application

Each application should contains configuration like below.

### Apache web server

The `DocumentRoot` path should be set to directory `/project/path/public/app/` in your configuration file.

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/myproject/public/app/

        ServerName myproject
        DirectoryIndex index.php

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
```

## Host configuration for multiple applications

The `DocumentRoot` path should be set to directory `/project/path/public/admin/` in your configuration file.

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/myproject/public/admin/

        ServerName admin
        DirectoryIndex index.php

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
```

Create the directory `/public/admin/` and place your `/assets` folder, `.htaccess` and `index.php` files to in it.

## index.php configuration for multiple applications

You need replace all namespaces.

```php
namespace Admin\Event;
namespace Admin\Middleware;
namespace Admin\Controller;
```
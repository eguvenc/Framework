
## Host configuration

Each application should contains configuration like below.

### Apache web server

In your configuration file `DocumentRoot` should be set to directory `/project/path/public/app/`.

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
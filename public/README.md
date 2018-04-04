
## Host configuration

Application should contains configuration like below.

### Apache web server

In your configuration file `DocumentRoot` should be set to directory `/project/path/public/`.

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/myproject/public/

        ServerName myproject
        DirectoryIndex index.php

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
```
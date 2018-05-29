
#!/bin/bash

wget http://pecl.php.net/get/mongodb-1.4.3.tgz
tar -xzf mongodb-1.4.3.tgz
sh -c "cd mongodb-1.4.3 && phpize && ./configure && sudo make install"
echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`'
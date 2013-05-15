#easyChat#
easyChat is a simple, yet highly plugable chat system.

##REQUIREMENTS##
- php 5.4
- mysqli
- memcached
- linux based server

##INSTALL##
1. run `php composer.phar install`
2. run `cp config.sample.php config.inc.php` and edit `config.inc.php` to your will.
3. run `cp sample.htaccess .htaccess` this will allow for pretty URLs, and is required.
4. edit `.htaccess` to fit your system.  Specifically, change the `RewriteBase`
2. run `php scripts/install.php`
3. run `php scripts/useradd.php youremail newpassword ADMIN` will add a user with: (email, password, role)

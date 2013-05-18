#easyChat#
easyChat is a simple, yet highly plugable chat system.

##REQUIREMENTS##
- php 5.4
- mysqli
- memcached
- mcrypt
- linux based server

##INSTALL##
1. run `php composer.phar install`
2. run `cp config.sample.php config.inc.php` and edit `config.inc.php` to your will.
3. run `cp sample.htaccess .htaccess` this will allow for pretty URLs, and is required.
4. edit `.htaccess` to fit your system.  Specifically, change the `RewriteBase`
2. run `php scripts/install.php`
3. run `php scripts/useradd.php youremail newpassword ADMIN` will add a user with: (email, password, role)


##RUN `bin/server.php` AS A SERVICE##
There are many ways to run this as a service.

For testing, it works to simply run `php bin/server.php` from command line, but the terminal session must stay open.

To solve this issue, it is good to runn the script as a service.  In Ubuntu you can use `upstart` (http://upstart.ubuntu.com/getting-started.html)

Ussing upstart, you could create a script called something like `\etc\init\easychat.conf`:
```
description "easychat"
author      "mfairchild365"

# used to be: start on startup
# until we found some mounts weren't ready yet while booting:
start on started mountall
stop on shutdown

# Automatically Respawn:
respawn
respawn limit 99 5

script
    # Not sure why $HOME is needed, but we found that it is:
    export HOME="/root"

    exec php /var/www/easychat/bin/server.php >> /var/log/easychat.log 2>&1
end script

post-start script
   # Optionally put a script here that will notifiy you node has (re)started
end script
```
The above script was taken largely from http://kvz.io/blog/2009/12/15/run-nodejs-as-a-service-on-ubuntu-karmic/

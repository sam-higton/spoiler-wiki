# spoiler-wiki
a wiki that hides spoilers

built with [Propel ORM](http://propelorm.org/) and [Slim](http://www.slimframework.com/). Developed using [Vagrant](http://vagrantup.com) with [Scotch Box](https://box.scotch.io/).

### Get up and running

1. `git clone git@github.com:sam-higton/spoiler-wiki.git /path/to/webroot/`
2. `cd /path/to/webroot`
3. `composer install`
4. `vagrant up`
    * if you're having issues in windows 10, [this](https://www.virtualbox.org/ticket/14040) is probably why, and make sure you're using the latest version of VirtualBox   
    * vagrant will probably complain about the connection timing out an awful lot. Just ignore it for a minute or so; it'll sort itself out eventually
    
5. You now need ssh into the virtual box (`vagrant ssh`) and open up mysql to external traffic. [this](http://stackoverflow.com/questions/15663001/remote-connections-mysql-ubuntu) explains it better than I can (todo: mess about with vagrant provisions to find a way of automating this)
6. double check the connection settings in `propel.json` are correct.
7. `vendor/bin/propel sql:build --overwrite`
8. `vendor/bin/propel sql:insert`

### updating the database

If the schema has been modified since the last commit, run the following to preserve your data:

1. `vendor/bin/propel diff`
2. `vendor/bin/propel migrate`
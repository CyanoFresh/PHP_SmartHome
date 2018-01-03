Smart Home control by Yii2 + Arduino + WebSockets (RatchetPHP)
============================

This is my personal app for control my smart house. built on Yii2 Framework, used RatchetPHP for WebSockets and all
it's tied with Arduino UNO with aREST library on it.

# Attention!!! This project is abandoned! Use https://github.com/CyanoFresh/SmartHome


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      components/         contains app components
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      servers/            contains servers classes
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

Get project files:

~~~
git clone https://github.com/CyanoFresh/home.git
cd home
composer install
php init
~~~

Then configure DB in config/db-local.php and run:

~~~
php yii migrate
~~~

Then configure WebSockets in `config/params.php`

# BulletScreen
Simple bullet screen application

##Table of contents
* [Quick Start](#quick-start)

##Quick Start

###What's included

With in the download, you'll find the following directories and files.

```
BulletScreen/
├── config.php
├── index.php
├── mananger.php
├── get.php
├── css/
|   ├── BulletScreen.css
│   ├── bootstrap.css
└── js/
    ├── BulletScreen.js
    └── bootstrap.min.js
```

###Environment

```
Memcached
PHP
Mysql
```

###Install

Edit the config file `config.php`, and replace the following key value.

```
define("DB_HOST", "localhost");
define("DB_NAME", "BulletScreen");
define("DB_USER", "root");
define("DB_PWD", "");

define("MEM_HOST", "localhost");
define("MEM_PORT", "11211");
```
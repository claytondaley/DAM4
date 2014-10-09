DAM4
====

Introduction
------------
DAM4 provides a uniquely powerful Digital Asset Management framework by wrapping the legacy ResourceSpace codebase in a Zend2 Framework wrapper layer. ResourceSpaace is a feature-right DAM tool, but the procedural codebase makes it difficult to extend using off-the-shelf modules. DAM4's wrapper layer makes it possible to extend ResourceSpace with OO modules available through the Zend2 Framework. 

Initial priorities include:

 - Authentication - Bridge Zend\Authentication to the RS codebase so Zend modules can be used for site-wide authentication.
 - Pageview Tracking - Capture legacy page requests as they pass through the Zend layer, getting around limitations in the legacy RS code arising from AJAX calls.
 - Enhanced Settings Experience - Many (MANY!) of the settings in Legacy RS are set using global variables in a config.php file. DAM4 will provide a single, GUI-based setting experience combining settings from the database and the config.php file.

Installation
------------

Due to its mixed heritage, DAM4 requires a combination of git and Composer:

    git clone git://github.com/claytondaley/DAM4.git --recursive
    cd ZendSkeletonApplication
    curl -s https://getcomposer.org/installer | php --
    php composer.phar install

Both LegacyRS and the ResourceSpace codebase are obtained as submodules of DAM4.

Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t / public/index.php

This will start the cli-server on port 8080, and bind it to all network interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/DAM4/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/DAM4/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

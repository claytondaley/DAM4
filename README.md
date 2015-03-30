DAM4
====

Introduction
------------
DAM4 provides a uniquely powerful Digital Asset Management (DAM) framework by wrapping the legacy ResourceSpace codebase in a Zend2 Framework wrapper layer. ResourceSpaace is a feature-rich DAM tool, but the procedural codebase makes it difficult to extend using off-the-shelf modules. DAM4's wrapper layer makes it possible to extend ResourceSpace with the many OO modules available through the Zend2 Framework.

Initial priorities include:

 - [x] Provide a transparent experience for ResourceSpace despite the ZF2 wrapper layer.
 - [x] Pageview Tracking - Capture legacy page requests as they pass through the Zend layer, getting around limitations in the legacy RS code arising from AJAX calls.  Opportunities for improvement include:
 - [x] (using ZfcUser) Authentication - Bridge Zend\Authentication to the RS codebase so Zend modules can be used for site-wide authentication.
     - [] Upgrade to ZfcUser 2.x branch (blocked by ZfcUserAdmin dependency)
     - [] Reset password experience
 - [x] (using ZfcUserAdmin) Enhanced Authentication - Add compatible user admin screens (then hijack legacy URLs)
     - [] Upgrade to support ZfcUser 2.x
 - [] Branding - Make it easy to brand aspects of the system including the logo, page titles, and other descriptions
     - [] Ensure branding extends to ZfcUser
     - [] Ensure branding extends to ZfcUserAdmin
     - [] Ensure branding extends to future ZF2 modules
 - [] Settings Experience - Many (MANY!) of the settings in Legacy RS are set using global variables in a config.php file. DAM4 intends to provide a single, GUI-based setting experience combining settings from the database and the config.php file.
 - [] Enhanced Tracking - Support for tracking geographic search (currently !geo... keyword gets tracked like any search term)
 - [] Enhanced Tracking - Support for dynamic results in advanced search (currently tracked when user clicks through to the result set)

Installation
------------

Due to its mixed heritage, DAM4 requires a combination of git and Composer:

    git clone git://github.com/claytondaley/DAM4.git --recursive
    cd DAM4
    curl -s https://getcomposer.org/installer | php --
    php composer.phar install

Several key components (including the ResourceSpace codebase) are obtained as submodules of DAM4 so you must also run:

    git submodule update --recursive --init

Web Server Setup
----------------

### Dreamhost, GoDaddy, and other retail providers

Setup a website (domain or subdomain) using the /public folder as the root of your website

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

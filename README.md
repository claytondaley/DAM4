DAM4
====

Introduction
------------
DAM4 provides a uniquely powerful Digital Asset Management (DAM) framework by wrapping the legacy ResourceSpace (RS) codebase in a Zend2 Framework wrapper layer. ResourceSpaace is a feature-rich DAM tool, but the procedural codebase makes it difficult to extend using off-the-shelf modules. DAM4's wrapper layer makes it possible to extend ResourceSpace with the many OO modules available through the Zend2 Framework.

Initial priorities include:

 - [x] Provide a transparent experience for ResourceSpace despite the ZF2 wrapper layer.
 - [x] Pageview Tracking - Capture legacy page requests as they pass through the Zend layer, getting around limitations in the legacy RS code arising from AJAX calls.  Opportunities for improvement include:
 - [x] Authentication (using ZfcUser) - Bridge Zend\Authentication to the RS codebase so Zend modules can be used for site-wide authentication.
     - [ ] Upgrade to ZfcUser 2.x branch (blocked by ZfcUserAdmin dependency)
     - [ ] Reset password experience
 - [x] Enhanced Authentication (using ZfcUserAdmin) - Add compatible user admin screens (then hijack legacy URLs)
     - [ ] Upgrade to support ZfcUser 2.x
 - [x] Branding - Make it easy to brand aspects of the system including the logo, page titles, and other descriptions
     - [x] Ensure branding extends to ZfcUser
     - [x] Ensure branding extends to ZfcUserAdmin
     - [ ] Ensure branding extends to future ZF2 modules
 - [x] Settings Experience (using ZF2 config files) - Many (MANY!) of the settings in Legacy RS are set using global variables in a config.php file. DAM4 intends to provide an enhanced setting experience.
     - [ ] GUI - Convert settings experience to a single, GUI-base
     - [ ] Database - Combine DAM4 and RS database settings
 - [ ] Enhanced Tracking - Support for tracking geographic search (currently !geo... keyword gets tracked like any search term)
 - [ ] Enhanced Tracking - Support for dynamic results in advanced search (currently tracked when user clicks through to the result set)

Installation
------------

Aquiring all of the DAM4 dependencies is now fully managed by composer!

    git clone git://github.com/claytondaley/DAM4.git
    cd DAM4
    curl -s https://getcomposer.org/installer | php --
    php composer.phar install

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
    
CONFIGURATION
=============

___NOTE: DUE TO DEPENDENCIES ON ZfcRbac an ZfcUser THIS PROCESS IS CURRENTLY BROKEN___

DAM4 contains a copy of the legacy RS codebase that starts uninitialized.  To maximize forward-compatibility, DAM4 (via LegacyRS) still delegates installation activities to the legacy codebase.  Navigate to the root of this installation and follow the standard RS [installation instructions](http://wiki.resourcespace.org/index.php/Installation).

Once you've installed the program, open `/vendor/resourcespace/resourcespace/include/config.php` and ensure that `spider_password`, `scramble_key`, and `api_key` are all set to random values.  This is critical to ensure that your installation is secure.

This same `config.php` file can be used to extensively customize the behavior of RS.  See the `corporate.config.php` discussion in the next section for some additional information.  

ZF2 CONFIG
==========

You have the option to use ZF2 to manage some ResourcSpace configurations.  This package includes helper config files to simplify this process:   

 - Inside `/vendor/resourcespace/resourcespace/include`, rename `config.php` to `legacy.config.php`
 - Copy `config.php.dist` from `/config/resourcespace` to the `/vendor/resourcespace/resourcespace/include` and remove the "dist" extension.
 - Copy `legacyrs.logcal.php.dist` from `/config` to your ZF2 application's autoload folder and remove the "dist" extension
 - Update the configuration inside `legacyrs.logcal.php`

The LegacyRS module includes a sample configuration for a corporate (hybrid internal-external) DAM deployment (`corporate.config.php`). This file would replace `legacy.config.php` in this configuration.

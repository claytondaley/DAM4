DAM4
====

Introduction
------------
DAM4 provides a uniquely powerful Digital Asset Management (DAM) framework by wrapping the legacy ResourceSpace (RS) codebase in a Zend2 Framework wrapper layer. ResourceSpaace is a feature-rich DAM tool, but the procedural codebase makes it difficult to extend using off-the-shelf modules. DAM4's wrapper layer makes it possible to extend ResourceSpace with the many OO modules available through the Zend2 Framework.

Initial priorities include:

 - [x] Provide transparent access to ResourceSpace pages despite the ZF2 wrapper layer (implemented in LegacyRS module)
 - [x] Pageview Tracking - Capture and record legacy page requests as they pass through the Zend layer (implemented in LegacyRS module)
 - [x] Provide a framework to hijack legacy URLs and substitute ZF2 pages (implemented in LegacyRS module)
     - [ ] Add the entire legacy URL tree to prepare for future expansions of ZF2 layer (taking over additional legacy functionality)
 - [x] Authentication (using ZfcUser) - Bridge Zend\Authentication to the RS codebase so Zend modules can be used for site-wide authentication
     - [x] Reset password experience
 - [x] User Administration (using ZfcUserAdmin) - Shift user administration responsibilities to ZF2 (important to enforce ZF2 password policy)
     - [x] Hijack legacy URLs so links in legacy interface work
 - [x] Branding - Make it easy to brand aspects of the system including the logo, page titles, and other descriptions
     - [x] Ensure branding extends to ZfcUser
     - [x] Ensure branding extends to ZfcUserAdmin
     - [ ] Ensure branding extends to future ZF2 modules
 - Enhanced Settings Experience:
     - [x] ZF2 Config - Manage legacy `config.php` settings using ZF2 config files 
     - [x] Database - Combine DAM4 and RS database settings (if ZF2 Config file is used) 
     - [ ] GUI - Convert settings experience to a GUI
 - [ ] Installation - Implement an appropriate installation process addressing the needs of both the RS and DAM4 layers 
 - [ ] Migration - Implement a migration script for existing RS users to upgrade to DAM4
 - [ ] Enhanced Page Tracking
     - [ ] Support for tracking geographic search (currently `!geo...` keyword gets tracked like any search term)
     - [ ] Track dynamic search results in advanced search (currently tracked only when user clicks through to the actual result set)

Installation
------------

Aquiring all of the DAM4 dependencies is now fully managed by composer!

    git clone git://github.com/claytondaley/DAM4.git
    cd DAM4
    curl -s https://getcomposer.org/installer | php --
    php composer.phar install

Once installed, basic DAM4 settings need to be configured:

    # Rename the config file to create a local version
    cp config/autoload/dam4.local.php.dist config/autoload/dam4.local.php
    # Modify the config file using your favorite text editor, e.g.
    vi config/autoload/dam4.local.php

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
    
Initial Setup
=============

___NOTE: DAM4 is does not have a full installation script yet.  You need to have an existing RS database and follow the instructions in the Migration section.___

During installation, DAM4 will import a copy of the legacy RS codebase that starts uninitialized.  To maximize forward-compatibility, DAM4 (via LegacyRS) still delegates installation activities to the legacy codebase.  Navigate to the root of this installation and follow the standard RS [installation instructions](http://wiki.resourcespace.org/index.php/Installation).

Once you've installed the program, open `/vendor/resourcespace/resourcespace/include/config.php` and ensure that `spider_password`, `scramble_key`, and `api_key` are all set to random values.  This is critical to ensure that your installation is secure.

This same `config.php` file can be used to extensively customize the behavior of RS.  See the `corporate.config.php` discussion (below) for some additional information.  

Migration
=========

To convert an existing RS installation to DAM4, (at least) the following is required:

 - Install DAM4 using the Installation instructions
 - Copy your old config.php file to `/vendor/resourcespace/resourcespace/include` (the usual place in the RS file structure)
     - Optionally, following the LegacyRS instructions to delegate configuration to ZF2 config files
 - Tweak your RS database (all changes are transparent to RS)
     - Extend the `password` column on the `user` table to 128 characters
     - Add `user` to the comma delineated list of permissions for all Usergroups (which function as ZF2 roles)
     - Ensure you have a usergroup named `guest` and add the permission `guest`
         - If you don't have a usergroup named `guest`, we recommend creating one with `id = -1`
 - Add the password reset table by running the script found in `\data\schema\goalio-forgotpassword.sql` against your database

The only major catch at this point is that legacy RS passwords won't work out-of-the-box.  Users will need to reset their passwords using the reset password option on the login screen.

ZF2-Managed RS Config
=====================

You have the option to use ZF2 to manage legacy RS configurations.  The LegacyRS module includes helper config files to simplify this process.  After completing the install process,

 - Inside `/vendor/resourcespace/resourcespace/include`, rename `config.php` to `legacy.config.php`
 - Copy `config.php.dist` from `vendor/claytondaley/legacyrs/config/resourcespace` to the `/vendor/resourcespace/resourcespace/include` and remove the "dist" extension.
 - Edit the LegacyRS section inside `/config/autoload/dam4.logcal.php`.  All values put here will be injected into the legacy `config.php`

The LegacyRS module also includes a sample configuration for a corporate (hybrid internal-external) DAM deployment.  ___This file also includes styling tweaks to help RS and DAM4 look more interchangeable so even migrations should consider reviewing this file.___ 

 - The file is found at `/vendor/claytondaley/legacyrs/config/resourcespace/corporate.config.php`
 - There are at least two ways to take advantage of these settings
     - (recommended) Move all your custom settings from your `legacy.config.php` into `dam4.local.php` and replace `legacy.config.php` with `corporate.config.php`
     - Manually move settings from `corporate.config.php` into your `legacy.config.php`.  Best to put the `corporate` stuff above your `legacy` stuff so your legacy stuff takes priority.

# Amarkal Framework [![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/) [![Amarkal Powered](http://www.askupasoftware.com/poweredby.gif)](http://www.askupasoftware.com/)

- Version: 0.2-alpha
- Website: [askupasoftware.com](http://www.askupasoftware.com/)
- Tested up to WordPress 4.0

## Contents

* [Overview](#overview)
* [Requirements](#requirements)
* [Installation](#installation)
	* [Via Git](#via-git)
	* [Via Composer](#via-composer)
	* [Manual install](#manual-install)
* [Updating](#updating)

## Overview

Amarkal is a WordPress development framework that is aimed at simplifying the creation and maintenance of WordPress plugins and themes.

This framework is currently in it's alpha stage and is not ready for production. This means that drastic changes can occur without prior notice. However, some parts of the framework have been fully matured and no major changes are expected for them.

## Requirements

- PHP version 5.3 or above
- WordPress 3.7 or above

## Installation

### Via git

1. Clone the Amarkal Git repository to the desired location:

		git clone git://github.com/amarkal/amarkal.git target-directory

	(Where `target-directory` is your desired folder path.)

### Via Composer

1. Create a file named composer.json at the root of your project, containing the Amarkal dependency:

		{
		  "require": {
		      "askupa-software/amarkal-framework": "dev-master"
		  }
		}

2. Install composer in your project:

		curl -s http://getcomposer.org/installer | php

3. Install dependencies

		php composer.phar install

### Manual install 

1. Download the zip archive from the GitHub repository page (or click here:  [amarkal.zip](https://github.com/amarkal/amarkal/archive/master.zip))
2. Unzip the package in the desired location

## Updating

Updating can be done by manually downloading the newer version, or by simply using `git pull` or `php composer.phar update`. The composer version of Amarkal is linked to the GitHub repository, so any updates to the framework will be reflected on both simultaneously.

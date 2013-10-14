Skpd\ProfilerToolbar, v1
=====================

[![Build Status](https://travis-ci.org/Skpd/profiler-toolbar.png?branch=master)](https://travis-ci.org/Skpd/profiler-toolbar)
[![Latest Stable Version](https://poser.pugx.org/Skpd/profiler-toolbar/v/stable.png)](https://packagist.org/packages/Skpd/profiler-toolbar)
[![Total Downloads](https://poser.pugx.org/Skpd/profiler-toolbar/downloads.png)](https://packagist.org/packages/Skpd/profiler-toolbar)

Introduction
------------

__Skpd\ProfilerToolbar__ is a module for Zend Framework 2, that adds profiler to the [Zend Developer Tools](https://github.com/zendframework/ZendDeveloperTools).

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (2.2.*)
* [Zend Developer Tools](https://github.com/zendframework/ZendDeveloperTools) 0.0.2
* [Xhprof](http://pecl.php.net/package/xhprof)

Installation
------------

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "skpd/profiler-toolbar": "dev-master"
    }
    ```

#### Post installation

1. Enable it in your `application.config.php` file.

    ```php
    return array(
        'modules' => array(
            'Skpd\ProfilerToolbar',
            // ...
        ),
    );
    ```

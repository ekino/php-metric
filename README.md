Ekino PHP Metric
================

[![Build Status](https://secure.travis-ci.org/ekino/php-metric.png?branch=master)](http://travis-ci.org/ekino/php-metric)

This library provides base classes to collect and publish metrics.

## Installation

### Using Composer

Use `composer.phar`:

```bash
$ php composer.phar require ekino/newrelic-bundle
```
You just have to specify the version you want : `master-dev`.
It will add the package in your `composer.json` file and install it.

Or you can do it by yourself, first, add the following to your `composer.json` file:

```js
// composer.json
{
    // ...
    require: {
        // ...
        "ekino/newrelic-bundle": "master-dev"
    }
}
```

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update ekino/php-metric
```

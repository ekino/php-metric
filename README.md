Ekino PHP Metric
================

[![Build Status](https://secure.travis-ci.org/ekino/php-metric.png?branch=master)](http://travis-ci.org/ekino/php-metric)

This library provides base classes to collect and publish metrics.

## Installation

### Using Composer

Use `composer.phar`:

```bash
$ php composer.phar require ekino/metric
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
        "ekino/metric": "dev-master"
    }
}
```

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update ekino/php-metric
```


## Usage with CollectD

```php
$collectd = new Ekino\Metric\Reporter\CollectDReporter('web1-php', new Ekino\Metric\Writer('localhost', 25826));
$manager = new Ekino\Metric\MetricManager($collectd);

$collector = new Ekino\Metric\Collector\MemoryUsageCollector(true);

// store the current memory usage
$manager->addMetric($collector->get());

// store execution time of one callback function
$heavy = function() { sleep(1); };
$collector = new Ekino\Metric\Collector\TimerFunctionCollector('php.function.heavy', $heavy);
$collector->run();

$manager->addMetric($collector->get());
```

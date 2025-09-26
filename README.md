# Bolt Weather Widget

Bolt Extension: A simple Dashboard Widget that displays the current weather. So you don't actually need to go outside or open the curtains.

> This repository was forked from [bobdenotter/weatherwidget](https://github.com/bobdenotter/weatherwidget) and updated to be used with newer versions of [bolt/core](https://github.com/bolt/core).

Installation:

```bash
composer require bolt/weatherwidget
```


## Running PHPStan and Easy Codings Standard

First, make sure dependencies are installed:

```
COMPOSER_MEMORY_LIMIT=-1 composer update
```

And then run ECS:

```
vendor/bin/ecs check src
```

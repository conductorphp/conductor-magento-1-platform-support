Conductor Magento 1 Platform Support Documentation
==================================================

This module adds [Magento 1](https://magento.com/) platform support for
[Conductor](https://github.com/conductorphp/conductor-core).

## Installation
```bash
composer require conductor/magento-1-platform-support
``` 

Run this command from your Conductor root to install the default build plan:
```bash
cp vendor/conductor/magento-1-platform-support/config/build-plans.php.dist config/autoload/build-plans.global.php
```

Run this command from your Conductor root to install the default snapshot plan:
```bash
cp vendor/conductor/magento-1-platform-support/config/snapshot-plans.php.dist config/autoload/snapshot-plans.global.php
```

Run this command from your Conductor root to install the default deployment plans:
```bash
cp vendor/conductor/magento-1-platform-support/config/deployment-plans.php.dist config/autoload/deployment-plans.global.php
```

## Media asset groups (CTAP-2146)

Plans exclude media paths by group, written `@name` in an asset's `excludes` (or `includes`) list.
Groups compose: list several, or mix them with literal paths.

| Group | Paths under `media` | Regenerable |
|---|---|---|
| `@cache` | `/catalog/category/cache`, `/catalog/product/cache`, `/catalog/placeholder/cache` | Yes: resized images |
| `@compiled` | `/css`, `/css_secure`, `/js`, `/js_secure` | Yes: merged CSS/JS |
| `@scratch` | `/captcha`, `/tmp` | Yes: short-lived files |
| `@import` | `/import` | **No**: import files are data |
| `@core` | all of the above | Mixed |

`@core` (and `@magento1_core`, which references it) is the union of the other four. A media backup
should exclude `@cache`, `@compiled` and `@scratch` instead, so it keeps `/import`.

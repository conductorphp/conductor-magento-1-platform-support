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

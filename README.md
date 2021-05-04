# Comfy Bundle
[![Build Status](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/oliverde8/comfyBundle/?branch=master)
[![Donate](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=oliverde8@gmail.com&lc=US&item_name=php-etl&no_note=0&cn=&curency_code=EUR&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted)
[![Latest Stable Version](https://poser.pugx.org/oliverde8/comfy-bundle/v)](//packagist.org/packages/oliverde8/comfy-bundle) 
[![Total Downloads](https://poser.pugx.org/oliverde8/comfy-bundle/downloads)](//packagist.org/packages/oliverde8/comfy-bundle) 
[![Latest Unstable Version](https://poser.pugx.org/oliverde8/comfy-bundle/v/unstable)](//packagist.org/packages/oliverde8/comfy-bundle) 
[![License](https://poser.pugx.org/oliverde8/comfy-bundle/license)](//packagist.org/packages/oliverde8/comfy-bundle)

This bundle introduces all the necessary logic in order to save administrable configuration in the database or in any 
other storage solution.

Configurations are stored by paths which allows them to be grouped when creating an interface to edit them. 
See Easy Admin integration [here](https://github.com/oliverde8/comfyEasyAdminBundle)

It also allows saving multiple values per config key, these are called **scopes**. By default there is a `default` 
scope and a scope per locale, locale scopes inherits values of the default scope. Scopes have levels. For example 
"French for France" inherits values from "French". So both "French for Canada" and "French for France" can be configured at once.

Each config is a unique service that can be autowired; yes Comfy loves:heart: symfony 4.4+ with it's autowiring. 

## Usage

### Install Bundle

```sh
composer require oliverde/comfy-bundle
```

If you want to use doctrine for storage you will need to install it, if not please refeer to the alternative storage section.

See our [getting srated guide](docs/getting-started.md).

## Advance usages

### Creating your own scope inheritance tree

[See here](docs/scope-resolver.md)

### Creating your own config type

[See here](docs/custom-config.md)

### Validating configs values

To add custom validations you need to create your own config type.

### Alternative storage solution

:construction:

## TODO

  
### Low priority
- [ ] Add more basic config types.
  - [ ] Ideas? Create a ticket
- [ ] Add command line to see configs & scopes
- [ ] Add caching per scope. Do this by creating a layered storage so that when we read we first read on the cacheStorage then the other solution.
- [ ] Add documentation.
  - [ ] How not to use doctrine but alternative solution.

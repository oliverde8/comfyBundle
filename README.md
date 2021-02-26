# Comfy Bundle

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

### Create configs

Declare a new service as fallows

```yml
  my_vendor.my_bundle.comfy.config1:
    class: oliverde8\ComfyBundle\Model\TextConfig
    arguments:
      $path: "test/test1"
      $name: "test.test1"
      $description: "test.test1_desc"
      $defaultValue: "Toto 1"
    tags:
      - "comfy.config"
```

The **service id**, will allow you to inject the configuration into the services you need. 
Thanks to autowiring this is trivial by just calling the argument `$myBundleConfig1`

The **path** as well as the name & description are used for the admin interfaces.

Finally, each config has a default value.

### Use the configs

In order to use the configs, get and set values you will need to simply inject them into your service.

```php
<?php
public function __construct(\oliverde8\ComfyBundle\Model\ConfigInterface $myBundleConfig1);
```

With autowiring the service id `my_vendor.my_bundle.comfy.config1` becomes `myBundleConfig1`. In order for this to work 
please respect the service naming pattern. 

You can now use it to get the config value:
```php
<?php
$this->myConfig->get();
```

> :bulb: The config service is responsible of transforming the data; so the data we get here depends on the type of config used.

This will return the config value for the current scope, which by default is the current locale. 

We can also fetch the value for any other scope.

```php
<?php
$this->myConfig->get("default/en_GB");
``` 

So even if we are on a "french" page the config will be the `en_gb` one; 

> If no value is set for a certain scope then the value of the parent scope will be returned. in the example above it's 
the `en` and if none is defined for `en` then it's the `default`.

we can also set values the same way; either for the current scope, or a particular scope.

```php
<?php
$this->myConfig->set($newValue);
$this->myConfig->set($newValue, "default/en_GB");
```

## Advance usages

### Creating your own scope inheritance tree

[See here](docs/scope-resolver.md)

### Creating your own config type

:construction:

### Validating configs values

:construction:

### Alternative storage solution

:construction:

## TODO

- [ ] Add composer.json file
- [ ] Add command line to see configs & scopes
- [ ] Form builder should take into account config scope limitation
- [ ] Add more basic config types.
    - [ ] Url
    - [ ] Boolean (Yes/No)
    - [ ] Other
- [ ] Add documentation.
    - [ ] Creating custom configs
    - [ ] Adding validation to configs.
    - [ ] How not to use doctrine but alternative solution.
- [ ] Add caching per scope. Do this by creating a layered storage so that when we read we first read on the cacheStorage then the other solution.

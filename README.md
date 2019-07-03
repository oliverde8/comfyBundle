# Comfy Bundle

This bundle introduces all the necessary logic in order to save admiistrable configuration in the database or in any 
other way. 

Configurations are stored by paths which allows them to be grouped when creating an interface to edit them. 

It also allows saving multiple values per config key, these are called scopes. By default a `default` scope and
a scope per locale is created, locale scopes inherits values of the default scope. 


## Principle

There is one service per configuration, so to get a config we need inject that configuration service, 
so to fetch multiple configurations we need to inject multiple services.   

**Scope** allows configurations to easily differ; for example if the title of the website is configurable we would
like it to differ between locales. 

## Usage

### Install Bundle

```sh
composer require oliverde/comfy-bundle
```



### Create configs

Declare a new service as fallows

```yml
  my_vendo.my_bundle.comfy.config1:
    class: oliverde8\ComfyBundle\Model\TextConfig
    arguments:
      $path: "test/test1"
      $name: "test.test1"
      $description: "test.test1_desc"
      $defaultValue: "Toto 1"
    tags:
      - "comfy.config"
```

The service id, will allow you to inject the configuration into the services you need. 

The path is used for the admin interface, as well as the name & description. 

Finally you config has a default value.

### Use the configs

In order to use the configs, get and set values you will need to simply inject them into your service.

```php
<?php
public function __construct(\oliverde8\ComfyBundle\Model\ConfigInterface $myConfig);
```

Autowiring will not work here, 

You can now use it to get the config value.

```php
<?php
$this->myConfig->get();
```

> The config service is responsible of transforming the data; so the data we get here depends on the type of config used.

This will return the config value for the current scope, which by default is the current locale. 

We can also fetch the value for any other scope.

```php
<?php
$this->myConfig->get("default/en_GB");
``` 

So even if we are on a "french" page the config will be the `en_gb` one; 

> If no value is set for a certain scope then the value of the parent scope will be returned. in the example above it's 
the `default`.

we can also set values the same way.

```php
<?php
$this->myConfig->set($newValue);
$this->myConfig->set($newValue, "default/en_GB");
``` 

## Advance usages

### Creating your own config type

### Validating configs

### Creating your own scope inheritance tree

## TODO

- [ ] Add composer.json file
- [ ] Add command line to see configs & scopes
- [ ] Add caching per scope. For doctrine add configs to make it possible.
- [ ] Add form builders for each config type.
- [ ] Add more basic config types.
- [ ] Add more documentation.
- [ ] Add sonata support.
- [ ] Add easy admin support.
- [ ] Add additional storage solutions. 
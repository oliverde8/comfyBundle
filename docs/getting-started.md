# Basic Usage

## Create configs

You will need to first create a new configuration. Todo this declare a service as fallows: 

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

## Use the configs

In order to use the configs, get and set values you will need to simply inject them into your service.

```php
<?php
public function __construct(\oliverde8\ComfyBundle\Model\ConfigInterface $myBundleConfig1);
```

With autowiring the service id `my_vendor.my_bundle.comfy.config1` becomes `myBundleConfig1`. In order for this to work
please respect the service naming pattern.

### Getting config values

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

### Saving new config values

We can also set values the same way; either for the current scope, or a particular scope.

First we should validate the new value:

```php
<?php
$results = $this->myConfig->validate($newValue);
if ($results->count() > 0) {
    // Do my thing.
}

?>
```

Once the value is validated we can set it.

```php
<?php
$this->myConfig->set($newValue);
$this->myConfig->set($newValue, "default/en_GB");
```


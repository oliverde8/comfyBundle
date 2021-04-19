# Creating a custom config

You will probably need to create custom configs quite often.  Most configs will extend `TextConfig` class as everything is stored as a text in the database.


## Creating custom config for custom validation

Most of the time we will create a new config to just add a custom validation constraint. 
In this example we will create a custom config with a custom regex validation.

First let's create our DemoConfig:

```php
class DemoConfig extends TextConfig
{
    protected function getValidationConstraints()
    {
        return new Regex(['pattern' => '.*']);
    }
}
```

Now let's set the validation constraint to use with the `getValidationConstraints` method;
```php

```


## Creating a complex custom config.

We will create a custom config to store an array. 

First let's create our SerializedDataConfig: 

```php
class SerializedDataConfig extends TextConfig
{

}
```

Because our value is an array we must transform it into a string to be able to store it in the database. We will use 
the `serialize` method to do this. We must always thing that our value can be null. If at a certain scope the value
is being inherited then it's null.

```php
    protected function serialize($value): ?string
    {
        return !is_null($value) ? json_encode($value) : null;
    }
```

When we read the value from the database we need to transform it into a string. Todo this we will use the `deserialize`
method. Again we might have a null value if the data is inherited. 

```php
    protected function deserialize(?string $value)
    {
        return !is_null($value) ? json_decode($value) : null;
    }
```

The validation will be executed on the "deserialized" data, we can therefore use a Collection validatior. 

### Form

If you are using this bundles FormType to edit your forms you will also need to create a new FormResolver, 

Let's create a FormResolver extending the base resolver and handle it in the `addTypeToForm` method

```php
class SerializedDataFormProvider extends SimpleFormProvider
{
    public function addTypeToForm(string $name, ConfigInterface $config, FormInterface $formBuilder, string $scope)
    {
        // Do my magic to create the form.
    }   
}
```
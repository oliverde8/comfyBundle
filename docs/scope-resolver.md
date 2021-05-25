# Creating my own Scope resolver

This is probably one of the first thing you will need to do. The default provider doesen't know which locales
you are using. It doesen't know if you differentiate English for the United states and English for the United Kingdom. 

You will therefore either use an existing provider with a custom settings or create your own. 

## Simple custom scopes 

If you have a simple list of scopes you can use the existing SimpleScopeResolver and configure it properly. 

```yml
  oliverde8\ComfyBundle\Resolver\ScopeResolverInterface:
    class: oliverde8\ComfyBundle\Resolver\SimpleScopeResolver
    arguments:
      "$defaultScope": 'default'
      "$scopes":
        'default': "Default"
        'default/website1': "First Website"
        'default/website1/en': "First Website - English"
        'default/website1/fr': "First Website - French "
        'default/website2': "Second Website - French"
        'default/website2/fr': "Second Website - French"
```

When using the simple scope resolver the `/` allows to create sub scopes. So configs made for the 'default/website1/en'
scope will inherit configs from the 'default/website1' scope which in turn inherits from `default` 

## Developing your own resolver. 

By creating your own scope resolver you will be able to define the inheritances and how the default scope is resolved.

The easiest to achieve this is to extend `oliverde8\ComfyBundle\Resolver\AbstractScopeResolver`. 

```php
    /**
     * @inheritDoc
     */
    public function getCurrentScope(): string
    {
        return "default";
    }

    /**
     * @inheritDoc
     */
    protected function initScopes(): array
    {
        return [
            'default': 'Default',
            'default/website1': 'Default Website 1',
            'default/website1/en': 'Default Website 1 - English',
            'default/website1/fr': 'Default Website 1 - French',
        ]
    }
```

The init scope method can make database queries if needed; the method is only called once per request. 
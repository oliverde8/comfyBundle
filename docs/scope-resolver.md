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
        'default': "Default", 
        'default/website1/en': "First Website - English"
        'default/website1/fr': "First Website - French "
        'default/website2/fr': "Second Website - French"
```

When using the simple scope resolver the `/` allows to create sub scopes. So configs made for the 'default/website1/en'
scope will inherit configs from the 'default/website1' scope which in turn inherits from `default` 

## Developing your own resolver. 

By creating your own scope resolver you will be able to define the inheritances and how the default scope is resolved.

Let's first create our Resolver class, this example assumes we have only a single "default scope".

```php
<?php 
namespace App\Resolver;

class ComfyCustomScopeResolver implements ScopeResolverInterface
{
    public function getCurrentScope(): string
    {
        return "default";
    }

    public function validateScope(string $scope = null): bool 
    {
        return $scope == "default";
    }

    public function getScope(string $scope = null): string
    {
        return "default";
    }

    public function inherits(string $scope = null)
    {
        return null;
    }

    public function getScopeTree() : array
    {
        return ['default' => ['~name' => "Default"]];
    }
}

```

Once our Resolver is created we can use the di to use our new class instead of the default one

```yml
  oliverde8\ComfyBundle\Resolver\ScopeResolverInterface:
    class: App\Resolver\ComfyCustomScopeResolver
```
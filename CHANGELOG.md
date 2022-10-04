# 1.2.0 

This version introduces multiple improvements for generating forms to change configurations by introducing new services
and by adding security voters for permissions. 

- :star2: Improved performance greately (some use cases divided per 10) when getting configuration values.
- :star2: Introduced new service `VisibleConfigsResolver` to check permissions when fetching configs.
- :star2: Introduced new voter `ConfigVoter` to allow user access to read or write on a config.
- :star2: Introduced new voter `ScopeVoter` to allow user access to read or write on a configs for a specific scope.
- :star2: Introduced command `comfy:config:get` to check configuration values from the command line.
- :star2: Introduced command `comfy:config:set` to change configuration values from the command line.
- :collision: Method `getRecursiveFirstConfigPath` of `ConfigDisplayManager` is now deprecated and shouldn't be used. The `VisibleConfigsResolver` should be used.  
- :wrench: Fix issue with `getScopeTree` failing on `AbstractScopeResolver` if `initScopes` was not called before. 
- :wrench: Fix issue `comfy:scope:list` command failing with some scope resolver implementations.

There are no Breaking Changes, so existing sources should behave as before. 

# 1.1.0

- :star2: Added new Entity config type.

# 1.0.0 

- :confetti_ball: :tada: First stable release :tada: :confetti_ball:
- :star2: Added Json config type. 
- :star2: Added support for symfony 6
- :wrench: Fix issue with `getRawValue` on configs returning always null.

# 1.0.0 Alhpa #4

- :star2: Improved quality of the form builders. **THIS INTRODUCES A BC TO FORM PROVIDERS**
- :wrench: Issue with usage of __destruct to flush entities causing issues with sylius and possibly other bundles/solutions.

# 1.0.0 Alpha #3

- :star2: Feature #3 - Improve SimpleScopeResolver to make it easier to create custom scope resolvers. 
- :wrench: Fix #2 - Missing dependency to `oliverde8/associative-array-simplified`.

# 1.0.0 Alpha #2

- :star2: Added Select & Multi select configs.
- :wrench: Fix issue with save of serialized values causing issues with the "use default" option. 

# 1.0.0 Alpha #1
- :confetti_ball: :tada: First release :tada: :confetti_ball:

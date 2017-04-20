# translators
Open laravel project to external translation

## Basic Installation

  - `composer require famousinteractive/translators`
  
  - Add `Famousinteractive\Translators\TranslatorsServiceProvider::class` in serviceProvider in config/app.php
    
  - Publish the config file : `php artisan vendor:publish` 
  
  - Launch the command `php artisan famousTranslators:initialize` to get a clientId and ApiKey and generate config file

## Using the Database content manager

In addition of the translators, you can use the database content manager in order to manage more content by using the database, always available by the API

   - Launch the migration : `php artisan migrate`
   
   - Use `fitrans($key = 'mypage.section1.title', [optional]  $default = 'default value', [optional]  $parameters = ['key' => 'value'], [optional] $lang = 'fr', [optional] $useCache = true)` in the view
    
    
## Good to know
    
If you want to use different api key for you local and production, take care to put the famousTranslator.php config file n your gitignore     
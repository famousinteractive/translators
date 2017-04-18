# translators
Open laravel project to external translation

## Basic Installation

  - `composer require famousinteractive/translators`
  
  - Add `Famousinteractive\Translators\TranslatorsServiceProvider::class` in serviceProvider in config/app.php
    
  - Publish the config file : `php artisan vendor:publish` 
  
  - Launch the command `php artisan famousTranslators:generateApiKey` to get a clientId and ApiKey

## Using the Database content manager

In addition of the translators, you can use the database content manager in order to manage more content by using the database, always available by the API

   - Launch the migration : `php artisan migrate`
   
   - Add `'Fitrans'   => Famousinteractive\Translators\Helpers\Trans::class` in your Alias in config/app.php file
   
   - Use `fit()` in the view
    
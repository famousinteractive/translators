# translators
Open laravel project to external translation

## Installation

  - `composer require famousinteractive/translators`
  
  - Add `Famousinteractive\Translators\TranslatorsServiceProvider::class` in serviceProvider in config/app.php

  - Publish the config file : `php artisan vendor:publish` 
  
  - Launch the command `php artisan famousTranslators:generateApiKey` to get a clientId and ApiKey

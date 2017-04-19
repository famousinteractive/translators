<?php

namespace Famousinteractive\Translators\Commands;

use Illuminate\Console\Command;


class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'famousTranslators:generateApiKey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an api Key for the translation platform';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('APi key generator');

        $clientId = str_random(40);
        $key = str_random(80);

        $lang = $this->ask('Enter the language to activate. Use a coma to separate them', 'nl,fr');
        $lang = explode(',', $lang);

        $credentials = [
            'clientId'    => $clientId,
            'key'         => \Hash::make($key),
            'lang'        => $lang,
        ];

        $fp = fopen(config_path('famousTranslator.php') , 'w');
        fwrite($fp, '<?php return ' . var_export( $credentials, true) . ';');
        fclose($fp);

        $this->alert('Your client id is ' . $clientId);
        $this->alert('Your key is ' . $key);

        $this->info('Keep them saved somewhere !');
    }
}

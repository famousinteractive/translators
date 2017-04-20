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
        $this->info('We\'ll ask you a few question to initialize this package. You can edit these data in the famousTranslator.php file in your config directory');

        $clientId = str_random(40);
        $key = str_random(80);

        //Enable language
        $lang = $this->ask('Enter the language to activate. Use a coma to separate them', 'nl,fr');
        $lang = explode(',', $lang);

        //Filestystem check
        $this->info('We\'ll now check the disks in you filesystem config.');

        $filesystem = include config_path('filesystems.php');
        $diskToUse = [];

        if(isset($filesystem['disks'])) {
            foreach($filesystem['disks'] as $disk=>$info) {
                $diskToUse[$disk] = $this->confirm('Disk "' . $disk . '" has been detected. Do you want to use it with this package ? ' );
            }
        }

        if(count($diskToUse) == 0) {
            $this->warn('You define no disk or none were defined in your config. The file manager will not be enabled. You still can enable it by adding disks and editing de famousTranslator config file');
            $diskToUse = false;
        }

        $credentials = [
            'clientId'    => $clientId,
            'key'         => \Hash::make($key),
            'lang'        => $lang,
            'disks'       => $diskToUse,
        ];

        $fp = fopen(config_path('famousTranslator.php') , 'w');
        fwrite($fp, '<?php return ' . var_export( $credentials, true) . ';');
        fclose($fp);

        $this->alert('Your client id is ' . $clientId);
        $this->alert('Your key is ' . $key);

        $this->info('Keep them saved somewhere !');
    }
}

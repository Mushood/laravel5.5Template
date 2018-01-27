<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateEntity extends Command
{
    protected $singularLowerCase;
    protected $pluralLowerCase;
    protected $singularFirstUpper;
    protected $pluralFirstUpper;

    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:entity {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a boilerplate for an entity created. You need to include a name in this format "singular/plural".';

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
        $name = $this->argument('name');
        $this->initialiseNameVariables($name);
        $this->initialiseFileNames();

        $this->info("Creating boilerplate for  {$this->singularLowerCase}!");

        $boilerplate = $this->checkBoilerplateFiles();
        if($boilerplate){
            $this->info("All boilerplate templates in place!");
        }

        $destination = $this->checkDestinationFiles();
        if(!$destination){
            $this->info("No boilerplate already place!");
        } else {
            $this->info("Boilerplate already place! Aborting");

            return false;
        }

        foreach ($this->files as $file){
            $boilerplateCreated = $this->createBoilerplate($file);
            if($boilerplateCreated){
                $this->info("Boilerplate created!");
            }
        }

        $this->info("Boilerplate created for {$this->singularLowerCase}!");
    }

    private function initialiseNameVariables($name)
    {
        $nameArray = explode("/", $name);
        //get singular and plural
        if(count($nameArray) == 2){
            $singular =  $nameArray[0];
            $plural = $nameArray[1];
        } else {
            $singular = $name;
            $plural = $name . 's';
        }
        //get singular/plural lowercase and first letter uppercase
        $this->singularLowerCase = strtolower($singular);
        $this->pluralLowerCase = strtolower($plural);
        $this->singularFirstUpper = ucfirst($singular);
        $this->pluralFirstUpper = ucfirst($plural);
    }

    private function initialiseFileNames()
    {
        $this->files = [
            'route' => [
                'source' => base_path('snippets/routes.php') ,
                'destination' => base_path('routes/' . $this->pluralLowerCase . '.php')
            ],
        ];
    }

    private function checkBoilerplateFiles()
    {
        $exists = false;
        $count = count($this->files);
        $index = 0;

        foreach ($this->files as $file){
            if(\File::exists($file['source'])){
                $index++;
            }
        }

        if($index == $count){
            $exists = true;
        }

        return $exists;
    }

    private function checkDestinationFiles()
    {
        $exists = true;
        $count = count($this->files);
        $index = 0;

        foreach ($this->files as $file){
            if(!\File::exists($file['destination'])){
                $index++;
            }
        }

        if($index == $count){
            $exists = false;
        }

        return $exists;
    }

    private function createBoilerplate($routes)
    {
        $sourceFile = $routes['source'];
        $destinationFile = $routes['destination'];

        \File::copy($sourceFile,$destinationFile);
        try
        {
            $contents = File::get($destinationFile);
            $contents = str_replace('entitys',$this->pluralLowerCase,$contents);
            $contents = str_replace('Entitys',$this->pluralFirstUpper,$contents);
            $contents = str_replace('entity',$this->singularLowerCase,$contents);
            $contents = str_replace('Entity',$this->singularFirstUpper,$contents);
            \File::put($destinationFile, $contents);
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            $this->info("Boilerplate could not be created!");
        }

        return true;
    }
}

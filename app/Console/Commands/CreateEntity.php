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

        $this->checkPublicFileForImages();

        foreach ($this->files as $file){
            $boilerplateCreated = $this->createBoilerplate($file);
            if($boilerplateCreated){
                $this->info("Boilerplate created!");
            }
        }

        $this->info("Boilerplate created for {$this->singularLowerCase}!");

        $this->info("Running composer dump autoload!");
        exec('composer dump-autoload');

        $this->info("Recompiling js!");
        exec('npm run dev');
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
                'directory' => base_path('routes'),
                'destination' => base_path('routes/' . $this->pluralLowerCase . '.php')
            ],
            'model' => [
                'source' => base_path('snippets/Entity.php') ,
                'directory' => base_path('app/Models'),
                'destination' => base_path('app/Models/' . $this->singularFirstUpper . '.php')
            ],
            'migration' => [
                'source' => base_path('snippets/2017_11_09_110801_create_entitys_table.php') ,
                'directory' => base_path('database/migrations'),
                'destination' => base_path('database/migrations/2017_11_09_110801_create_' . $this->pluralLowerCase . '_table.php')
            ],
            'controller' => [
                'source' => base_path('snippets/controller/EntityController.php') ,
                'directory' => base_path('app/Http/Controllers'),
                'destination' => base_path('app/Http/Controllers/' . $this->singularFirstUpper . 'Controller.php')
            ],
            'admin_controller' => [
                'source' => base_path('snippets/controller/AdminEntityController.php') ,
                'directory' => base_path('app/Http/Controllers/Admin'),
                'destination' => base_path('app/Http/Controllers/Admin/Admin' . $this->singularFirstUpper . 'Controller.php')
            ],
            'view_show' => [
                'source' => base_path('snippets/views/entity/front/show.blade.php') ,
                'directory' => base_path('resources/views/' . $this->singularLowerCase ),
                'destination' => base_path('resources/views/' . $this->singularLowerCase . '/show.blade.php')
            ],
            'admin_create' => [
                'source' => base_path('snippets/views/entity/admin/create.blade.php') ,
                'directory' => base_path('resources/views/admin/' . $this->singularLowerCase ),
                'destination' => base_path('resources/views/admin/' . $this->singularLowerCase . '/create.blade.php')
            ],
            'admin_index' => [
                'source' => base_path('snippets/views/entity/admin/index.blade.php') ,
                'directory' => base_path('resources/views/admin/' . $this->singularLowerCase ),
                'destination' => base_path('resources/views/admin/' . $this->singularLowerCase . '/index.blade.php')
            ],
            'index' => [
                'source' => base_path('snippets/views/entity/front/index.blade.php') ,
                'directory' => base_path('resources/views/' . $this->singularLowerCase ),
                'destination' => base_path('resources/views/' . $this->singularLowerCase . '/index.blade.php')
            ],
            'view_show_js' => [
                'source' => base_path('snippets/views/entity/front/show.vue') ,
                'directory' => base_path('resources/assets/js/components/' . $this->singularLowerCase ),
                'destination' => base_path('resources/assets/js/components/' . $this->singularLowerCase . '/show.vue')
            ],
            'admin_create_js' => [
                'source' => base_path('snippets/views/entity/admin/create.vue') ,
                'directory' => base_path('resources/assets/js/components/' . $this->singularLowerCase ),
                'destination' => base_path('resources/assets/js/components/' . $this->singularLowerCase . '/create.vue')
            ],
            'admin_index_js' => [
                'source' => base_path('snippets/views/entity/admin/list.vue') ,
                'directory' => base_path('resources/assets/js/components/' . $this->singularLowerCase ),
                'destination' => base_path('resources/assets/js/components/' . $this->singularLowerCase . '/list.vue')
            ],
            'index_js' => [
                'source' => base_path('snippets/views/entity/front/list_front.vue') ,
                'directory' => base_path('resources/assets/js/components/' . $this->singularLowerCase ),
                'destination' => base_path('resources/assets/js/components/' . $this->singularLowerCase . '/list_front.vue')
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
            if(!\File::isDirectory($file['directory'])){
                File::makeDirectory($file['directory']);
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

    private function checkPublicFileForImages()
    {
        $publicFolder = public_path('images/' . $this->pluralLowerCase);

        if(!\File::isDirectory($publicFolder)){
            File::makeDirectory($publicFolder);
        }
    }
}

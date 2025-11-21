<?php

namespace Antikode\AntiCrud\Console;

use Illuminate\Console\Command;

class Generator extends Command
{
    protected $signature = 'anti:crud {name}
                            {--table= : The name of the Table.}
                            {--model= : The name of the Model.}';

    protected $description = 'CRUD Generator for Laravel9 using Metronic';

    protected $path = '';

    public function __construct()
    {
        parent::__construct();

        $this->path = config('anti-crud.prefix_path').'/'.$this->name.'.php';
    }

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $modelName = $this->option('model') ? $this->option('model') : $this->name;
        $tableName  = $this->option('table');

        if (file_exists(app_path($this->path))) {
            if ($this->confirm('File is exist!. Do you want to overwrite ?')) {
                // Create
            } else {
                $this->info('Process Stop!');
            }
        } else {
            // Create
        }
    }
}

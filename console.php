<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected static $defaultName = 'db:migrate';

    protected function configure(){
        $this
            ->setDescription('Activaties the migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if(file_exists('migrate.php')){
            require('migrate.php');

            $output->writeln("<info>Migrated successfully</info>");
            return Command::SUCCESS;
        }

        $output->writeln("<error>File migrate.php doesn't exists!</error>");
        return Command::FAILURE;
    }


}

class RollbackCommand extends Command
{
    protected static $defaultName = 'db:rollback';

    protected function configure(){
        $this
            ->setDescription('Activaties the rollbacks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if(file_exists('rollback.php')){
            require('rollback.php');

            $output->writeln("<info>Rollback is successfull</info>");
            return Command::SUCCESS;
        }

        $output->writeln("<error>File rollback.php doesn't exists!</error>");
        return Command::FAILURE;
    }


}

class MakeMigrationCommand extends Command
{
    protected static $defaultName = 'db:migration';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new migration')
            ->addArgument('name', InputArgument::REQUIRED, 'name of migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $Dir = __DIR__ . '/db/Migrations';
        $Path = "$Dir/$name.php";

        if (!is_dir($Dir)) {
            mkdir($Dir, 0777, true);
        }

        if (file_exists($Path)) {
            $output->writeln("<error>Migration $name already exists!</error>");
            return Command::FAILURE;
        }

        $template = <<<PHP
<?php

namespace DB\Migrations;

use Core\Classes\QueryBuilder;


class $name
{
    public function migrate()
    {
        //QueryBuilder::execute("");
    }

    public function rollback(){
        //QueryBuilder::destroy('');
    }
}
PHP;

        file_put_contents($Path, $template);
        $output->writeln("<info>Migration $name created: $Path</info>");

        return Command::SUCCESS;
    }
}

class MakeControllerCommand extends Command
{
    protected static $defaultName = 'app:controller';

    protected function configure()
    {
        $this
            ->setDescription('creates a new controller')
            ->addArgument('name', InputArgument::REQUIRED, 'name of Controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $controllerName = ucfirst($name) . 'Controller';
        $controllerDir = __DIR__ . '/app/Controllers';
        $controllerPath = "$controllerDir/$controllerName.php";

        if (!is_dir($controllerDir)) {
            mkdir($controllerDir, 0777, true);
        }

        if (file_exists($controllerPath)) {
            $output->writeln("<error>Controller $controllerName already exists!</error>");
            return Command::FAILURE;
        }

        $template = <<<PHP
<?php

namespace App\Controllers;

class $controllerName
{
    public function index()
    {
        // TODO: реализовать метод
    }
}
PHP;

        file_put_contents($controllerPath, $template);
        $output->writeln("<info>Controller $controllerName created: $controllerPath</info>");

        return Command::SUCCESS;
    }
}

class MakeModelCommand extends Command
{
    protected static $defaultName = 'app:model';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new model')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the model');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = ucfirst($input->getArgument('name'));
        $table = strtolower($name) . 's'; // Простая логика для имени таблицы
        $modelDir = __DIR__ . '/app/Models';
        $modelPath = "$modelDir/$name.php";

        if (!is_dir($modelDir)) {
            mkdir($modelDir, 0777, true);
        }

        if (file_exists($modelPath)) {
            $output->writeln("<error>Model $name already exists!</error>");
            return Command::FAILURE;
        } 

        $template = <<<PHP
        <?php

        namespace App\Models;

        class $name extends Model
        {
            protected static \$table = '$table';
        }
        PHP;

        file_put_contents($modelPath, $template);
        $output->writeln("<info>Model $name created: $modelPath</info>");

        return Command::SUCCESS;
    }
}

$application = new Application('console');
$application->add(new MakeControllerCommand());
$application->add(new MakeModelCommand());
$application->add(new MakeMigrationCommand());
$application->add(new MigrateCommand());
$application->add(new RollbackCommand());
$application->run();

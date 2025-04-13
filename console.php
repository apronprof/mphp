<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected static $defaultName = 'app:controller';

    protected function configure()
    {
        $this
            ->setDescription('Создаёт новый контроллер')
            ->addArgument('name', InputArgument::REQUIRED, 'Имя контроллера');
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
            $output->writeln("<error>Контроллер $controllerName уже существует!</error>");
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
        $output->writeln("<info>Контроллер $controllerName создан: $controllerPath</info>");

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
            $output->writeln("<error>Модель $name уже существует!</error>");
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
        $output->writeln("<info>Контроллер $name создан: $modelPath</info>");

        return Command::SUCCESS;
    }
}

$application = new Application('console');
$application->add(new MakeControllerCommand());
$application->add(new MakeModelCommand());
$application->run();

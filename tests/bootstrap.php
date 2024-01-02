<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$commands = [
    'doctrine:database:drop --force',
    'doctrine:database:create',
    'do:mi:mi -n',
];

foreach ($commands as $command) {
    \passthru(
        \sprintf(
            'APP_ENV=%s php "%s%s..%sbin%sconsole" %s',
            $_ENV['APP_ENV'],
            __DIR__,
            \DIRECTORY_SEPARATOR,
            \DIRECTORY_SEPARATOR,
            \DIRECTORY_SEPARATOR,
            $command
        )
    );
}

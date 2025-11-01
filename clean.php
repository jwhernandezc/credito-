<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$commands = [
    'config:clear',
    'route:clear',
    'view:clear',
    'cache:clear',
    'optimize:clear',
    'config:cache',
];

foreach ($commands as $command) {
    $exitCode = $kernel->call($command);
    echo "Command {$command} executed with exit code {$exitCode} <br>";
}

echo "✅ Cache operations completed!";

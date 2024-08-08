<?php

declare(strict_types=1);

require __DIR__ . '/../../autoload.php';

use App\Console\Commands\RefreshDataCommand;

$refreshDataCommand = new RefreshDataCommand();

$refreshDataCommand->handle();

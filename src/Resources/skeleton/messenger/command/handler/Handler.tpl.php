<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use <?= $commandNameWithNamespace; ?>;

class <?= $class_name ?><?= "\n" ?>
{
    public function __construct()
    {
    }

    /**
     * @param <?= $commandName; ?> $command
     *
     * @return void
     */
    public function __invoke(<?= $commandName; ?> $command): void
    {
    }
}

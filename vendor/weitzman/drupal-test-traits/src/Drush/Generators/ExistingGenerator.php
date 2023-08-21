<?php

namespace weitzman\DrupalTestTraits\Drush\Generators;

use DrupalCodeGenerator\Command\ModuleGenerator;

/**
 * Implements `generate test:existing` command.
 */
class ExistingGenerator extends ModuleGenerator
{
    protected string $name = 'test:existing';
    protected string $alias = 'ex-test';
    protected string $description = 'Generates an ExistingSite test';
    protected string $templatePath = __DIR__;

    /**
     * {@inheritdoc}
     */
    protected function generate(array &$vars): void
    {
        $this->collectDefault($vars);
        $vars['class'] = $this->ask('Class', '{machine_name|camelize}Test');
        $this->addFile('tests/src/ExistingSite/{class}.php', 'existing.php.twig');
    }
}

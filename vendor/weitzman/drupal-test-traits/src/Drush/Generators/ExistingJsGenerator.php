<?php

namespace weitzman\DrupalTestTraits\Drush\Generators;

use DrupalCodeGenerator\Command\ModuleGenerator;

class ExistingJsGenerator extends ModuleGenerator
{
    protected string $name = 'test:existing-js';
    protected string $alias = 'exjs-test';
    protected string $description = 'Generates an ExistingSiteSelenium2Driver test';
    protected string $templatePath = __DIR__;

    /**
     * {@inheritdoc}
     */
    protected function generate(array &$vars): void
    {
        $this->collectDefault($vars);
        $vars['class'] = $this->ask('Class', '{machine_name|camelize}Test');
        $this->addFile('tests/src/ExistingSiteJavascript/{class}.php', 'existing-js.php.twig');
    }
}

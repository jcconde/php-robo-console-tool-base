<?php

/**
 * @copyright Copyright © 2024 Semain. All rights reserved.
 * @author    Juan Carlos Conde <juancarlosc@onetree.com>
 */

declare(strict_types=1);

namespace Semajo\Project\Test;

use Robo\Symfony\ConsoleIO;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class TestCommand extends \Robo\Tasks
{
    public function hello(ConsoleIO $inputOutput, $world): void
    {
        $inputOutput->say("Hello, $world!");
    }
}

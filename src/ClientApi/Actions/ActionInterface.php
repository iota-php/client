<?php declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Actions;

interface ActionInterface
{
    public function execute(array $options = []);
}

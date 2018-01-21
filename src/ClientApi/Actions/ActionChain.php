<?php declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Actions;

class ActionChain
{
    private $actions;

    /**
     * @param $actions ActionInterface[]
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function execute(string $actionClassName, array $options = [])
    {
        foreach ($this->actions as $action) {
            if ($action instanceof $actionClassName) {
                return $action->execute($options);
            }
        }

        throw new \RuntimeException(sprintf('No action called "%s" found in ClientApi.', $actionClassName));
    }
}

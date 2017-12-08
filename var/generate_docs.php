<?php

// A UGLY BUT WORKING DOCs GENERATOR, eat that..
require_once __DIR__ . '/../vendor/autoload.php';

use PhpParser\Error;
use PhpParser\ParserFactory;

class P extends \PhpParser\NodeVisitorAbstract
{
    protected $ns;
    protected $nsFull;
    protected $searchParams = false;
    protected $isTrait = false;
    protected $structure = [];
    protected $valid = false;

    public function enterNode(\PhpParser\Node $node) {
        if ($this->isTrait && count($this->structure) && $node instanceof \PhpParser\Node\Param) {
            $this->searchParams = true;
            $this->structure['params'] = $this->structure['params'] ?? [];
            $latest = [
                'name' => $node->name,
                'type' => is_string($node->type) ? $node->type : implode('\\', $node->type->parts)
            ];


            $a = 'B';
            switch(get_class($node->default)) {
                case \PhpParser\Node\Expr\ConstFetch::class:
                    $latest['default'] = $node->default->name->parts[0];
                    break;
                case \PhpParser\Node\Expr\Array_::class:
                    $latest['default'] = '[]';
                    break;
                case \PhpParser\Node\Scalar\LNumber::class:
                    $latest['default'] = $node->default->value;
                    break;
                case \PhpParser\Node\Expr\UnaryMinus::class:
                    $latest['default'] = $node->default->expr->value * -1;
                    break;
                default:
                    $latest['default'] = false;

            }
            $this->structure['params'][] = $latest;
            echo $latest['name'] . ': ' . $latest['type'] . ' - ' . $latest['default'] . "\n";
        }
        if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
            $this->ns = strtolower($node->name->parts[count($node->name->parts) - 1]);
            $this->nsFull = $node->name->parts;
        }
        if ($node instanceof \PhpParser\Node\Stmt\Trait_) {
            $this->isTrait = true;
        }

        if ($node instanceof \PhpParser\Node\Stmt\ClassMethod &&
            substr($node->name,0,3) !== 'set' &&
            strtolower($node->name) === $this->ns)
        {
            $this->valid = $this->isTrait();
            echo $node->name . "\n" . str_repeat('-', 50) . "\n\n";
            $this->structure['name'] = $node->name;
        }
    }

    /**
     * @return mixed
     */
    public function getNs()
    {
        return $this->ns;
    }

    /**
     * @return mixed
     */
    public function getNsFull()
    {
        return $this->nsFull;
    }

    /**
     * @return bool
     */
    public function isSearchParams(): bool
    {
        return $this->searchParams;
    }

    /**
     * @return bool
     */
    public function isTrait(): bool
    {
        return $this->isTrait;
    }

    /**
     * @return array
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }


}

$directory = __DIR__ . '/../src';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));


$loader = new Twig_Loader_Filesystem(__DIR__);
$twig = new Twig_Environment($loader, array(
));

$it->rewind();
$ps = [];
while($it->valid()) {
    /** @var $it SplFileInfo */

    $file = $it->key();
    $code = file_get_contents($file);

    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    try {
        $ast = $parser->parse($code);
    } catch (Error $error) {
        echo "Parse error: {$error->getMessage()}\n";
        return;
    }

    $traverser = new \PhpParser\NodeTraverser();
    $traverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver()); // our own node visitor
    $traverser->addVisitor($p = new P());
    $traverser->traverse($ast);

    $it->next();

    if($p->isValid()) {
        $ps[] = $p;
    }
}

$clientApiMethods = [];
$remoteApiMethods = [];
foreach($ps as $p) {
    if (in_array('RemoteApi', $p->getNsFull(), true)) {
        $remoteApiMethods[] = $p->getStructure()['name'];
    }
    if (in_array('ClientApi', $p->getNsFull(), true)) {
        $clientApiMethods[] = $p->getStructure()['name'];
    }
}

foreach($ps as $p) {
    $path = __DIR__ . '/../examples/kitchen_sink';
    if(in_array('RemoteApi', $p->getNsFull(), true)) {
        $path .= '/remote_api';
    } else if(in_array('ClientApi', $p->getNsFull(), true)) {
        $path .= '/client_api';
    }

    if (!@mkdir($path) && !is_dir($path)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
    }

    $file = $path . '/' . $p->getStructure()['name'] . '.php';
    file_put_contents($file,
        $twig->render('generate_kitchensink.html.twig', [
            'params' => $p->getStructure()['params'],
            'method' => $p->getStructure()['name'],
            'type' => in_array('RemoteApi', $p->getNsFull(), true) ? 'remote' : 'client',
            'clientApiMethods' => $clientApiMethods,
            'remoteApiMethods' => $remoteApiMethods,
        ])
    );
}
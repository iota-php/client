# Kickstart

**The IOTA instance**

The IOTA instance is the central point of initialization of the library. While 
it is possible to use much of the functionality without the instance, it will 
serve as convenient entry point for the library’s functionality.

To initialize a new IOTA instance and start with your IOTA project you’ll have 
to do the following.

```php
<?php

use IOTA\Client;
use IOTA\Node;
use IOTA\DI\IOTAContainer;
use IOTA\RemoteApi\RemoteApi;
use IOTA\ClientApi\ClientApi;

$options = [
    'ccurlPath' => '/srv/ccurl'
];

// initializes a new IOTA instance with the built in container and one iota node
$container = new IOTAContainer($options);

$iota = new Client(
    $container->get(RemoteApi::class),
    $container->get(ClientApi::class),
    [new Node('http://node01.iotatoken.nl:14265')]
);
```

Now you can use the IOTA instance to get access to the client and remote api. 

```php
<?php

// ..initialization..
// call the remote api
$iota->getRemoteApi()->methodX('params');

// call the client api
$iota->getClientApi()->methodY('params');
```

**IOTAContainer**

When you initialize the IOTA instance, the first parameter is a container that 
is used to initialize instances of certain objects. You can use your own DI 
container if you want, the only restriction is that it implements the 
`ContainerInterface` provided by the PSR-11 Standard.

If you want to use your own container (e.g. Symfony or Pimple or..), it’s your 
job to keep the instantiation config up-to-date. The built-in `IOTAContainer` 
is sufficient for 99% of the installations. If you don’t plan to switch 
implementation details, just keep using the `IOTAContainer`.

The `IOTAContainer` needs the following options passed as array keys:

- `ccurlPath` The path to the ccurl executables.

**Node**

A node defines the details of an IOTA node. It needs at least the host address, 
but can also hold more information:

```php
<?php
public Node::__construct(
    $host = 'http://localhost:14265',
    bool $doesPOW = false,
    int $apiVersion = 1,
    bool $sandbox = false,
    string $token = null
)
```

- `$host` The host of the Node.
- `$doesPOW` A flag indicating whether the node performs the POW. If it does 
   not, the POW will be performed locally.
- `$apiVersion`  The API version of the node.
- `$sandbox`  A flag indicating whether the node is a sandbox node.
- `$token` A token sent as authorization header.

You can but you don’t necessarily need to initialize the IOTA instance with one 
or more nodes but it’s convenient to hold them in there, because you’ll need to 
access it quite often. 

If you provide more than one node, calling the `getNode` method of the IOTA 
instance will return a random node from the list or from a specified position in 
the list.

You can also call `getLatestNode()` which will return the last used node.

It should be mentioned that all subsequent requests issued by a call of a 
library method will use the same Node! It is generally advised to just use one 
node during all requests, because not every node is in sync all the time and 
the results might can differ.

Right now it’s hard to find a good list of publicly available nodes, so I talked 
to someone on slack if it would be fine to mention his nodes in this 
documentation. He agreed, so feel free to use the nodes mentioned here as a 
starting point:

http://www.iotatoken.nl/

Please consider donating some IOTA for his efforts! 

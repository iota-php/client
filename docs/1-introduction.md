# IOTA-PHP Documentation

This project is an unofficial IOTA client library implementation in PHP.

**Please be aware that this library is in an early development state and the API 
of the library as well as the IOTA protocol is subject to change, so you might 
end up with errors on updates for now. Call it alpha, it is NOT ready to use in 
production.**

However, I encourage you to try it out and find bugs or suggest improvements. 
Apart from that, happy feeless money transferring and welcome to the future!

## Precaution: Security

Seeds are the only thing that protects the IOTAs of users. Please **DO NOT 
(NEVER EVER..)** transfer seeds over the web without taking appropriate security 
measures like e.g. SSL. And, if possible, never save the provided seeds anywhere 
unless you absolutely know what you are doing. Please take the same measures as 
if you would handle other payment data like credit cards and so on.

## Caveats

Proof of work is done by ccurl, therefore you’ll need this tool too. I did not 
put any effort in developing a port for this as it would never be as efficient 
as when it’s done natively. You can, however, implement your own version or use 
another implementation.

## Installation

**Composer**

Add a reference to the iota-php/client package in your composer.json file:

```json
{
    "require": {
        "iota-php/client": "dev-master"
    }
}
```

**Proof of work**
Proof of Work can be done locally if the node does not provide access to the 
`attachToTangle` command endpoint (which would do the POW for you). Currently we 
are using the ccurl implementation but it is easy to switch to another 
implementation if you have one.

You have to compile ccurl by yourself by following the instructions here: 
https://github.com/iotaledger/ccurl - the path to the executable will be needed
later on when working with the library.

## Example projects

The best way to kickstart the development is to have a look at the examples 
provided here: https://github.com/iota-php/examples

The example projects are **NOT** meant to run as a real application, just 
examples on how to use the library. They are **not secure** in any way and 
should only be tested in a **development environment** and with test seeds. 
Their only purpose is to showcase the library’s functionality.

## Learn IOTA

While this documentation will cover some basics of IOTA, it will not serve as
a tutorial for IOTA itself.

There are numerous resources out there which will help you, the best starting 
point is the discord chat.

## Initialization

**The IOTA instance**

The IOTA instance is the central point of initialization of the library. While 
you can use much of the functionality without it, it will serve as convenient 
entry point for the library’s functionality.

To initialize a new IOTA instance and start with your IOTA project you’ll have 
to do the following.

```php
<?php

use IOTA\DI\IOTAContainer;

$options = [
    'ccurlPath' => '/srv/ccurl'
];

// initializes a new IOTA instance with the built in container and one iota node
$iota = new Techworker\IOTA\IOTA(
    new IOTAContainer($options), 
    [new Node('http://node01.iotatoken.nl:14265')]
);
```

Now you can use the IOTA instance to get access to the client and remote api. 


    <?php
    
    // ..initialization..
    // call the remote api
    $iota->getRemoteApi()->methodX('params');
    
    // call the client api
    $iota->getClientApi()->methodY('params');

**The IOTAContainer**
When you initialize the IOTA instance, the first parameter is a container that is used to initialize instances of certain objects. You can use your own DI container if you want, the only restriction is that it implements the `ContainerInterface` provided by the PSR-11 Standard.

If you want to use your own container (e.g. Symfony or Pimple or..), it’s your job to keep the instantiation config up-to-date. The built-in `IOTAContainer` is sufficient for 99% of the installations. If you don’t plan to switch implementation details, just keep using the `IOTAContainer`.

The `IOTAContainer` needs the following options passed as array keys:


- `ccurlPath` The path to the ccurl executables.

**Node**
A node defines the details of an IOTA node. It needs at least the host address, but can also hold more information:


    <?php
    public Node::__construct(
        $host = 'http://localhost:14265',
        bool $doesPOW = false,
        int $apiVersion = 1,
        bool $sandbox = false,
        string $token = null
    )


- `$host` The host of the Node.
- `$doesPOW` A flag indicating whether the node performs the POW. If it does not, the POW will be performed locally.
- `$apiVersion`  The API version of the node.
- `$sandbox`  A flag indicating whether the node is a sandbox node.
- `$token` A token sent as authorization header.

You can but you don’t necessarily need to initialize the IOTA instance with one or more nodes but it’s convenient to hold them in there, because you’ll need to access it quite often. 

If you provide more than one node, calling the `getNode` method of the IOTA instance will return a random node from the list or from a specified position in the list.

You can also call `getLatestNode()` which will return the last used node.

It should be mentioned that all subsequent requests issued by a call of a library method will use the same Node! It is generally advised to just use one node during all requests, because not every node is in sync all the time and the results might can differ.

Right now it’s hard to find a good list of publicly available nodes, so I talked to someone on slack if it would be fine to mention his nodes in this documentation. He agreed, so feel free to use the nodes mentioned here as a starting point:

http://www.iotatoken.nl/

Please consider donating some IOTA for his efforts! 

## Types

This library provides a list of value types to ease the identification of, and work with, object values. 

**Trytes**
This is the most frequently used value type. It serves as a base type for various other value types and holds a collection of Trytes as a string.


    $trytes = new Trytes('ABC...');


**Seed**
A seed identifies an account and is the most security sensitive part of an IOTA implementation. Therefore I took some security measurements so the seeds won’t accidentally show up in logs etc. `print_r`, `var_dump`, `__toString` won’t return the original seed. If you really need to access the seeds trytes, you can call `getSeed()` on the instance.


    $seed = new Seed('ABC...'); // 81 trytes

**Address**
TODO: Documentation

**Approvee**
TODO: Documentation

**Bundle**
TODO: Documentation

**BundleHash**
TODO: Documentation

**HMACKey**
TODO: Documentation

**Input**
TODO: Documentation

**Iota**
TODO: Documentation

**Milestone**
TODO: Documentation

**Neighbor**
TODO: Documentation

**SecurityLevel**
TODO: Documentation

**SignatureMessageFragment**
TODO: Documentation

**Tag**
TODO: Documentation

**Tip**
TODO: Documentation

**Transaction**
TODO: Documentation

**TransactionHash**
TODO: Documentation

**Transfer**
TODO: Documentation

## Remote API

If you have a working IOTA instance, you can start to call the official node API commands described [here](https://iota.readme.io/v1.2.0/reference).

**addNeighbors**


    function addNeighbors(
        Node $node, 
        string[] $neighborUris
    ) : IOTA\RemoteApi\Commands\AddNeighbors\Response


- **Node $node**
  The node where the request gets send to.
- **string[] $neighborUris**
  A list of neighbor uris.

Adds one or more neighbors to the given node. This is only temporary for the node, they will be removed from your set of neighbors after you relaunch IRI.

Use the --neighbors parameter in your node configuration to permanently add neighbors.

**Example:**


    $iota->getRemoteApi()->addNeighbors(
        $iota->getNode(), ['udp://123.456.789.111:14265']
    );

**attachToTangle**


    function attachToTangle(
        Node $node,
        Transaction[] $transactions,
        TransactionHash $trunkTransaction,
        TransactionHash $branchTransaction,
        int $minWeightMagnitude
    ): IOTA\RemoteApi\Commands\AttachToTangle\Response


- **Node $node**
  The node where the request gets send to.
- **Transaction[] $transactions**
  The transactions to attach.
- **TransactionHash $trunkTransaction**
  The hash of the trunk-transaction previously obtained by `getTransactionsToApprove`.
- **TransactionHash $branchTransaction**
  The hash of the branch-transaction previously obtained by `getTransactionsToApprove`.
- **int $minWeightMagnitude**
  The difficulty.

This method attaches the specified transactions to the Tangle by doing Proof of Work together with the trunk- and branch transaction using the given weight magnitude. If the provided node blocks requests to attachToTangle (see `Node::doesPOW()`), the POW will be performed by one of the `PowInterface` implementations.

**Example:**


    $iota->getRemoteApi()->attachToTangle(
        $iota->getNode(), 
        [new Transaction('TRYTES..')],
        new TransactionHash('ABC'),
        new TransactionHash('DEF'),
        15
    );



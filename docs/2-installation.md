# Installation

**Composer**

https://packagist.org/packages/techworker/iota-php

**Proof of work**

Proof of Work can be done locally if the node does not provide access to the 
`attachToTangle` command endpoint (which would do the POW for you). Currently we 
are using the iotaledger/ccurl implementation but it is easy to switch to another 
implementation if you have one.

You have to compile ccurl by yourself by following the instructions here: 
https://github.com/iotaledger/ccurl


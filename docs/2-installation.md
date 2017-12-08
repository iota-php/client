# Installation

**Composer**

The library is not on packagist yet until we tag the very first version, so 
you'll have to manually add the repository.

```json
    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/techworker/iota-php"
            }
        ],
        "require": {
            "techworker/iota-php": "dev-master"
        }
    }
```
Then run `composer update` to install the dependency.

**Node JS keccak384**

The nodejs server to generate the keccak384 hashes can be found in the 
`/node-keccak` folder. 

Run `npm install` in this folder, followed by `node keccak384-srv.js` to start 
the server. I did not put much effort in it and as said in the introduction, 
this should only be a temporary solution until we have a proper implementation.

**Proof of work**

Proof of Work can be done locally if the node does not provide access to the 
`attachToTangle` command endpoint (which would do the POW for you). Currently we 
are using the iotaledger/ccurl implementation but it is easy to switch to another 
implementation if you have one.

You have to compile ccurl by yourself by following the instructions here: 
https://github.com/iotaledger/ccurl


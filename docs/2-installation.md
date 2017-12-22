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

**Proof of work**

Proof of Work can be done locally if the node does not provide access to the 
`attachToTangle` command endpoint (which would do the POW for you). Currently we 
are using the iotaledger/ccurl implementation but it is easy to switch to another 
implementation if you have one.

You have to compile ccurl by yourself by following the instructions here: 
https://github.com/iotaledger/ccurl


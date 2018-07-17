# Big Chain DB - PHP Driver

> This is a rewrite of the Big Chain DB - JS Driver, 
> written in PHP, the functionality and interaction is almost if not the same as the JS Driver


> Main repo: https://git.ryuu.technology/KryuuCommon/BigChainDB

# [![php-bigchaindb-driver](media/repo-banner@2x.png)](https://github.com/bigchaindb/js-bigchaindb-driver/blob/master/media/repo-banner%402x.png)

> unofficial PHP driver for [BigchainDB](https://github.com/bigchaindb/bigchaindb) to create transactions in PHP.

[Driver API reference (in progress)](API.md)

## Compatibility

| BigchainDB Server | BigchainDB PHP Driver        |
| ----------------- |------------------------------|
|                   | Still under development      |

## Table of Contents

  - [Installation and Usage](#installation-and-usage)
     - [Example: Create a transaction](#example-create-a-transaction)
  - [BigchainDB Documentation](#bigchaindb-documentation)
  - [Speed Optimizations](#speed-optimizations)
  - [Development](#development)
  - [Authors](#authors)
  - [License](#license)

---

## Installation and Usage

```bash
#composer require kryuu-common/big-chain-db-driver (comming soon, for now please use git)
```

```php
```

### Example: Create a transaction

```php
use KryuuCommon\BigChainDB\Driver;

$driver = new Driver();

// BigchainDB server instance (e.g. https://test.bigchaindb.com/api/v1/)
$API_PATH = 'http://localhost:9984/api/v1/'

// Create a new keypair.
$alice = new $driver->Ed25519Keypair()

// Construct a transaction payload
$tx = $driver->getTransaction()->makeCreateTransaction(
    // Define the asset to store, in this example it is the current temperature
    // (in Celsius) for the city of Berlin.
    [ 'city' => 'Berlin, DE', 'temperature' => 22, 'datetime' => date('D M d Y H:i:s \G\M\TO (T)')],

    // Metadata contains information about the transaction itself
    // (can be `null` if not needed)
    [ 'what' => 'My first BigchainDB transaction' ],

    // A transaction needs an output
    [ $driver->getTransaction()->makeOutput(
            $driver->getTransaction()->makeEd25519Condition($alice->publicKey))
    ],
    $alice->publicKey
)

// Sign the transaction with private keys
$txSigned = $driver->getTransaction->signTransaction($tx, $alice->privateKey)

// Send the transaction off to BigchainDB
$conn = $driver->Connection($API_PATH);

$conn->postTransactionCommit($txSigned)
    ->then(funrtion($retrievedTx) { 
        print_r( 'Transaction: ' . $retrievedTx->id . 'successfully posted.')
    });
```

## BigchainDB Documentation

- [The Hitchhiker's Guide to BigchainDB](https://www.bigchaindb.com/developers/guide/)
- [HTTP API Reference](https://docs.bigchaindb.com/projects/server/en/latest/http-client-server-api.html)
- [The Transaction Model](https://docs.bigchaindb.com/projects/server/en/latest/data-models/transaction-model.html?highlight=crypto%20conditions)
- [Inputs and Outputs](https://docs.bigchaindb.com/projects/server/en/latest/data-models/inputs-outputs.html)
- [Asset Transfer](https://docs.bigchaindb.com/projects/py-driver/en/latest/usage.html#asset-transfer)
- [All BigchainDB Documentation](https://docs.bigchaindb.com/)

## Authors

* inspired by [`js-bigchaindb-quickstart`](https://github.com/sohkai/js-bigchaindb-quickstart) of @sohkhai [thanks]
* re-written from [`js-bigchaindb-driver`](https://github.com/bigchaindb/js-bigchaindb-driver)
* Anders Blenstrup-Pedersen @ [`katsuoryuu.org`](https://katsuoryuu.org/)

## License

```
MIT
```
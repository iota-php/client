<?php

namespace Techworker\IOTA\Apps\Explorer;

use Techworker\IOTA\RemoteApi\Commands\FindTransactions;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\Type\BundleHash;

/** @var RemoteApi $api */
$api = include __DIR__.'/bootstrap.php';

/** @var FindTransactions\Response $transactions */
$transactions = $api->findTransactions([new BundleHash($_GET['b'])]);

?>

<h2>Bundle info</h2>
<h3>Bundle Hash</h3>
<p><?php echo $_GET['b']; ?></p>
<h3>Transactions in this bundle</h3>
<?php foreach ($transactions->getTransactionHashes() as $transaction) : ?>
<p><a href="transaction.php?t=<?php echo $transaction; ?>"><?php echo $transaction; ?></a></p>
<?php endforeach; ?>
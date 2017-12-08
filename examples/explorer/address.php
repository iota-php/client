<?php

namespace Techworker\IOTA\Apps\Explorer;

use Techworker\IOTA\IOTA;
use Techworker\IOTA\RemoteApi\Commands\GetBalances;
use Techworker\IOTA\RemoteApi\Commands\FindTransactions;
use Techworker\IOTA\Type\Address;

/** @var IOTA $iota */
$iota = include __DIR__ . '/bootstrap.php';

/** @var GetBalances\Response $balanceInfos */
$balanceInfos = $iota->getRemoteApi()->getBalances([new Address($_GET['a'])]);
if ($balanceInfos->isError()) {
    die(print_r($balanceInfos));
}

// better mapping will follow later on
$balance = array_combine([$_GET['a']], $balanceInfos->getBalances())[$_GET['a']];

/** @var FindTransactions\Response $transactions */
$transactions = $api->findTransactions([], [new Address($_GET['a'])]);

?>

<h2>Address info</h2>
<h3>Address</h3>
<p><?= $_GET['a'] ?></p>
<h3>Balance</h3>
<p><?=$balance ?></p>
<h3>Latest confirmed milestone</h3>
<p><?= $balanceInfos->getMilestone() ?> (<?= $balanceInfos->getMilestone()->getIndex() ?>)</p>
<h3>Transactions</h3>
<?php foreach ($transactions->getTransactionHashes() as $transaction) : ?>
<p><a href="transaction.php?t=<?= $transaction ?>"><?= $transaction ?></a></p>
<?php endforeach; ?>
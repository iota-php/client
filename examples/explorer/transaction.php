<?php

namespace Techworker\IOTA\Apps\Explorer;

use Techworker\IOTA\IOTA;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\RemoteApi\Commands\GetTrytes;
use Techworker\IOTA\RemoteApi\Commands\GetNodeInfo;
use Techworker\IOTA\RemoteApi\Commands\GetInclusionStates;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;


include __DIR__ . '/design/head.php';

/** @var IOTA $iota */
$iota = include __DIR__ . '/bootstrap.php';

/** @var GetTrytes\Response $transactionInfos */
$transactionInfos = $iota->getRemoteApi()->getTrytes($iota->getNode(), [new TransactionHash($_GET['t'])]);
if ($transactionInfos->isError()) {
    die(print_r($transactionInfos));
}

$transaction = new Transaction($transactionInfos->getTransactions()[0]);

/** @var GetNodeInfo\Response $nodeInfo */
$nodeInfo = $iota->getRemoteApi()->getNodeInfo($iota->getNode());

/** @var GetInclusionStates\Response $statesInfo */
$statesInfo = $iota->getClientApi()->getLatestInclusion($iota->getNode(),
    [$transaction->getTransactionHash()]
)->getStates();

?>

<h2>Transaction Detils</h2>
<h3>Transaction Hash</h3>
<p><?= $transaction->getTransactionHash() ?></p>
<h3>Confirmed</h3>
<p><?=current($statesInfo) ? 'true' : 'false' ?></p>
<h3>Address</h3>
<p><a href="address.php?a=<?= $transaction->getAddress() ?>"><?= $transaction->getAddress() ?></a></p>
<h3>Value</h3>
<p><?= $transaction->getValue()->getAmount() ?></p>
<h3>Tag</h3>
<p><?= $transaction->getTag() ?></p>
<h3>Timestamp</h3>
<p><?= $transaction->getTimestamp() ?></p>
<h3>Current Index in Bundle</h3>
<p><?= $transaction->getCurrentIndex() ?></p>
<h3>Last Index of Bundle</h3>
<p><?= $transaction->getLastIndex() ?></p>
<h3>Trunk Transaction Hash</h3>
<p><a href="transaction.php?t=<?= $transaction->getAddress() ?>"><?= $transaction->getTrunkTransactionHash() ?></a></p>
<h3>Branch Transaction Hash</h3>
<p><a href="transaction.php?t=<?= $transaction->getAddress() ?>"><?= $transaction->getBranchTransactionHash() ?></a></p>
<h3>Bundle Hash</h3>
<p><a href="bundle.php?b=<?= $transaction->getBundleHash() ?>"><?= $transaction->getBundleHash() ?></a></p>
<h3>Nonce</h3>
<p><?= $transaction->getNonce() ?></p>
<h3>Message or Signature</h3>
<p><?= $transaction->getSignatureMessageFragment() ?></p>
<h3>Raw</h3>
<p><?= $transaction ?></p>
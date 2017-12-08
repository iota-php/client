<?php

namespace Techworker\IOTA\Apps\Explorer;

include __DIR__ . '/design/head.php';

use Techworker\IOTA\IOTA;

/** @var IOTA $iota */
$iota = include __DIR__ . '/bootstrap.php';

$nodeInfo = $iota->getRemoteApi()->getNodeInfo($iota->getNode());
if ($nodeInfo->isError()) {
    die(print_r($nodeInfo));
}

$transactionsToApprove = $iota->getRemoteApi()->getTransactionsToApprove($iota->getNode(), 1);
if ($transactionsToApprove->isError()) {
    die(print_r($transactionsToApprove));
}

?>
<h1>Node info</h1>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Property</th>
        <th scope="col">Value</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Name</td>
        <td><?= $nodeInfo->getAppName() ?></td>
    </tr>
    <tr>
        <td>Version</td>
        <td><?= $nodeInfo->getAppVersion() ?></td>
    </tr>
    <tr>
        <td>Latest Milestone</td>
        <td>
            <a href="transaction.php?t=<?= $nodeInfo->getLatestMilestone() ?>">
                <?= $nodeInfo->getLatestMilestone() ?>
            </a><br />
            Index: <?= $nodeInfo->getLatestMilestone()->getIndex() ?>
        </td>
    </tr>
    <tr>
        <td>Lastest Solid Subtangle Milestone</td>
        <td>
            <a href="transaction.php?t=<?= $nodeInfo->getLatestSolidSubtangleMilestone() ?>">
                <?= $nodeInfo->getLatestSolidSubtangleMilestone() ?>
            </a><br />
            Index <?= $nodeInfo->getLatestSolidSubtangleMilestone()->getIndex() ?>
        </td>
    </tr>
    <tr>
        <td>Neighbors</td>
        <td><?= $nodeInfo->getNeighbors() ?></td>
    </tr>
    <tr>
        <td>Time</td>
        <td><?= $nodeInfo->getTime() ?></td>
    </tr>
    <tr>
        <td>Tips</td>
        <td><?= $nodeInfo->getTips() ?></td>
    </tr>
    <tr>
        <td>Packet Queue Size</td>
        <td><?= $nodeInfo->getPacketQueueSize() ?></td>
    </tr>
    <tr>
        <td>JRE Total Memory</td>
        <td><?= $nodeInfo->getJreTotalMemory() ?></td>
    </tr>
    <tr>
        <td>JRE Max Memory</td>
        <td><?= $nodeInfo->getJreMaxMemory() ?></td>
    </tr>
    <tr>
        <td>JRE Free Memory</td>
        <td><?= $nodeInfo->getJreFreeMemory() ?></td>
    </tr>
    <tr>
        <td>JRE Avail. Processors</td>
        <td><?= $nodeInfo->getJreAvailableProcessors() ?></td>
    </tr>
    <tr>
        <td>Transactions to request</td>
        <td><?= $nodeInfo->getTransactionsToRequest() ?></td>
    </tr>
    <tr>
        <td>Trunk Transaction</td>
        <td><a href="transaction.php?t=<?= $transactionsToApprove->getTrunkTransaction() ?>"><?= $transactionsToApprove->getTrunkTransaction() ?></a></td>
    </tr>
    <tr>
        <td>Branch Transaction</td>
        <td><a href="transaction.php?t=<?= $transactionsToApprove->getBranchTransaction() ?>"><?= $transactionsToApprove->getBranchTransaction() ?></a></td>
    </tr>
    </tbody>
</table>
<?php include __DIR__ . '/design/foot.php'; ?>
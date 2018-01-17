<?php

namespace Techworker\IOTA\Apps\Explorer;

include __DIR__.'/design/head.php';

use Techworker\IOTA\IOTA;

/** @var IOTA $iota */
$iota = include __DIR__.'/bootstrap.php';

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
        <td><?php echo $nodeInfo->getAppName(); ?></td>
    </tr>
    <tr>
        <td>Version</td>
        <td><?php echo $nodeInfo->getAppVersion(); ?></td>
    </tr>
    <tr>
        <td>Latest Milestone</td>
        <td>
            <a href="transaction.php?t=<?php echo $nodeInfo->getLatestMilestone(); ?>">
                <?php echo $nodeInfo->getLatestMilestone(); ?>
            </a><br />
            Index: <?php echo $nodeInfo->getLatestMilestone()->getIndex(); ?>
        </td>
    </tr>
    <tr>
        <td>Lastest Solid Subtangle Milestone</td>
        <td>
            <a href="transaction.php?t=<?php echo $nodeInfo->getLatestSolidSubtangleMilestone(); ?>">
                <?php echo $nodeInfo->getLatestSolidSubtangleMilestone(); ?>
            </a><br />
            Index <?php echo $nodeInfo->getLatestSolidSubtangleMilestone()->getIndex(); ?>
        </td>
    </tr>
    <tr>
        <td>Neighbors</td>
        <td><?php echo $nodeInfo->getNeighbors(); ?></td>
    </tr>
    <tr>
        <td>Time</td>
        <td><?php echo $nodeInfo->getTime(); ?></td>
    </tr>
    <tr>
        <td>Tips</td>
        <td><?php echo $nodeInfo->getTips(); ?></td>
    </tr>
    <tr>
        <td>Packet Queue Size</td>
        <td><?php echo $nodeInfo->getPacketQueueSize(); ?></td>
    </tr>
    <tr>
        <td>JRE Total Memory</td>
        <td><?php echo $nodeInfo->getJreTotalMemory(); ?></td>
    </tr>
    <tr>
        <td>JRE Max Memory</td>
        <td><?php echo $nodeInfo->getJreMaxMemory(); ?></td>
    </tr>
    <tr>
        <td>JRE Free Memory</td>
        <td><?php echo $nodeInfo->getJreFreeMemory(); ?></td>
    </tr>
    <tr>
        <td>JRE Avail. Processors</td>
        <td><?php echo $nodeInfo->getJreAvailableProcessors(); ?></td>
    </tr>
    <tr>
        <td>Transactions to request</td>
        <td><?php echo $nodeInfo->getTransactionsToRequest(); ?></td>
    </tr>
    <tr>
        <td>Trunk Transaction</td>
        <td><a href="transaction.php?t=<?php echo $transactionsToApprove->getTrunkTransaction(); ?>"><?php echo $transactionsToApprove->getTrunkTransaction(); ?></a></td>
    </tr>
    <tr>
        <td>Branch Transaction</td>
        <td><a href="transaction.php?t=<?php echo $transactionsToApprove->getBranchTransaction(); ?>"><?php echo $transactionsToApprove->getBranchTransaction(); ?></a></td>
    </tr>
    </tbody>
</table>
<?php include __DIR__.'/design/foot.php'; ?>
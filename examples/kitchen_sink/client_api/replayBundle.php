<?php

namespace Techworker\IOTA\Apps\KitchenSink;

/** @var \Techworker\IOTA\IOTA $iota */
$iota = include __DIR__.'/../bootstrap.php';

if (isAjax()) {
    try {
        $node = $iota->getNodes()[$_POST['node']];
        if ('' !== $_POST['tailTransactionHash']) {
            $tailTransactionHash = new \Techworker\IOTA\Type\TransactionHash($_POST['tailTransactionHash']);
        } else {
            $tailTransactionHash = null;
        }
        if ('' !== $_POST['depth']) {
            $depth = (int) $_POST['depth'];
        } else {
            $depth = null;
        }
        if ('' !== $_POST['minWeightMagnitude']) {
            $minWeightMagnitude = (int) $_POST['minWeightMagnitude'];
        } else {
            $minWeightMagnitude = null;
        }

        $result = $iota->getClientApi()->replayBundle(
            $node,
            $tailTransactionHash,
            $depth,
            $minWeightMagnitude
        );
        sendJson($result->serialize());
    } catch (\Exception $ex) {
        sendJson(['error' => $ex->getMessage()]);
    }
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>IOTA Explorer.</title>

    <link rel="stylesheet" href="/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="/iota-php.css">
    <style>
        body {
            min-height: 75rem;
        }
    </style>
    <script src="/jquery-3.2.1.js"></script>

    <script src="/highlight.min.js"></script>
    <script src="/json.min.js"></script>
    <link rel="stylesheet" href="/monokai-sublime.min.css" />
</head>

<body>

<main role="main" class="container">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <a class="navbar-brand" href="#">IOTA-PHP KitchenSink</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Client API</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown09">
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/sendTrytes.php">sendTrytes</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getTransfers.php">getTransfers</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getBundle.php">getBundle</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getBundlesFromAddresses.php">getBundlesFromAddresses</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/findTransactionObjects.php">findTransactionObjects</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getNewAddress.php">getNewAddress</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/isReAttachable.php">isReAttachable</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/promoteTransaction.php">promoteTransaction</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getAddresses.php">getAddresses</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getAccountData.php">getAccountData</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/storeAndBroadcast.php">storeAndBroadcast</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/sendTransfer.php">sendTransfer</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getLatestInclusion.php">getLatestInclusion</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/replayBundle.php">replayBundle</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/broadcastBundle.php">broadcastBundle</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getTransactionObjects.php">getTransactionObjects</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/client_api/getInputs.php">getInputs</a>
                                            </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Remote API</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown10">
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getTrytes.php">getTrytes</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/interruptAttachingToTangle.php">interruptAttachingToTangle</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getTips.php">getTips</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/attachToTangle.php">attachToTangle</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/addNeighbors.php">addNeighbors</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/isTailConsistent.php">isTailConsistent</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getNodeInfo.php">getNodeInfo</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/broadcastTransactions.php">broadcastTransactions</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getBalances.php">getBalances</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/findTransactions.php">findTransactions</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/storeTransactions.php">storeTransactions</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getNeighbors.php">getNeighbors</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getInclusionStates.php">getInclusionStates</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/removeNeighbors.php">removeNeighbors</a>
                                                    <a class="dropdown-item" href="/kitchen_sink/remote_api/getTransactionsToApprove.php">getTransactionsToApprove</a>
                                            </div>
                </li>
            </ul>
        </div>
    </nav>

    <div style="border: 1px solid #f0f0f0; padding: 10px; margin-bottom: 20px;">
        <p></p>
        <p><pre>public function replayBundle(
    Techworker\IOTA\Node $node,
    Techworker\IOTA\Type\TransactionHash $tailTransactionHash,
    int $depth,
    int $minWeightMagnitude
) : \Techworker\IOTA\ClientApi\Actions\ReplayBundle\Result</pre></p>
    </div>
    <div class="form-group">
        <label for="node">Node</label>
        <select class="form-control" id="node" name="node">
            <?php foreach ($iota->getNodes() as $k => $node) : ?>
            <option value="<?php echo $k; ?>"><?php echo $node->getHost(); ?></option>
            <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Select a node where the remote requests (commands) will be executed on.</small>
    </div>

    <div class="form-group">
        <label for="tailTransactionHash">tailTransactionHash</label>
        <input type="text" class="form-control" id="tailTransactionHash" name="tailTransactionHash" aria-describedby="tailTransactionHash" placeholder="" value="">
    </div>
    <div class="form-group">
        <label for="depth">depth</label>
        <input type="number" class="form-control" name="depth" id="depth" value="">
    </div>
    <div class="form-group">
        <label for="minWeightMagnitude">minWeightMagnitude</label>
        <input type="number" class="form-control" name="minWeightMagnitude" id="minWeightMagnitude" value="">
    </div>
<button id="submit" type="submit" class="btn btn-primary">Submit</button>

<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 30px;">
    <li class="nav-item">
        <a class="nav-link active" id="json-tab" data-toggle="tab" href="#json" role="tab" aria-controls="json" aria-selected="true">JSON result</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="performance-tab" data-toggle="tab" href="#performance" role="tab" aria-controls="performance" aria-selected="false">Performance</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="json" role="tabpanel" aria-labelledby="json-tab">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
        </div>
        <pre><code class="json" id="result"></code></pre>
    </div>
    <div class="tab-pane fade" id="performance" role="tabpanel" aria-labelledby="profile-tab">
        performance
    </div>
</div>

<script>
    $('#submit').on('click', function(e) {
        $(".spinner").show();
        var data = {
                                            node: $("#node").val(),                                                                tailTransactionHash: $("#tailTransactionHash").val(),                                                                depth: $("#depth").val(),                                                                minWeightMagnitude: $("#minWeightMagnitude").val()                                    };
                                                                        
        $.post(window.location.href,data)
            .done(function(data) {
                $(".spinner").hide();
                $("#result").html(JSON.stringify(data, null, 2));
                $('pre code').each(function(i, block) {
                    hljs.highlightBlock(block);
                });
            });
    });
    $(".spinner").hide();
</script>

</main>

<script src="/popper.min.js"></script>
<script src="/bootstrap.min.js"></script>
</body>
</html>
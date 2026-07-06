<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yeelo ERP: <?= $this->fetch('title') ?></title>
    
    <!-- Using HTML helper to fetch CSS -->
    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <!-- ELEMENT: Top Navigation -->
    <?= $this->element('top_nav') ?>

    <main class="main">
        <div class="container">
            <div class="row">
                <div class="column column-25">
                    <!-- ELEMENT: Sidebar -->
                    <?= $this->element('sidebar') ?>
                </div>
                <div class="column column-75">
                    <?= $this->Flash->render() ?>
                    <!-- TEMPLATE: Main Content -->
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Using HTML helper to fetch JS -->
    <?= $this->fetch('script') ?>
</body>
</html>

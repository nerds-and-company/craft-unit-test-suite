<?php

require_once __DIR__ . '/../../craft/app/tests/bootstrap.php';

$testSuiteLoader = new UnitTestSuite\Loader\AbstractTestLoader();
$testSuiteLoader->requireFiles(); // load files
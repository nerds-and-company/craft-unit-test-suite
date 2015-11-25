<?php

require_once __DIR__ . '/../../../craft/app/tests/bootstrap.php';

$loader = require_once __DIR__ . '/../../autoload.php';

$testSuiteLoader = new NerdsAndCompany\CraftUnitTestSuite\Loader\AbstractTestLoader();
$testSuiteLoader->requireFiles(); // load files
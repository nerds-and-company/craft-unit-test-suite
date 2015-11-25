<?php

require_once __DIR__.'/../../craft/app/tests/bootstrap.php';
require_once __DIR__.'/tests/UnitTestSuite_AbstractTest.php';
require_once __DIR__.'/classes/AbstractTestLoader.php';

$testSuiteLoader = new \Unittestsuite\AbstractTestLoader();
$testSuiteLoader->requireFiles(); // load files
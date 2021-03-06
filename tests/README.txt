Piwik comes with unit tests, integration tests, Javascript tests and Webtests.
This document briefly describes how to use and modify Piwik tests. 
 
 HOW TO RUN PIWIK TESTS
=======================
To run tests, you must use the SVN trunk. Tests files are not in the Piwik zip archive.
You can get the latest SVN at: http://dev.piwik.org/svn/
To execute the tests:
* Go to tests/index.php to see the various tests available
* Run all unit tests and integration tests by calling the file tests/all_tests.php in your browser.
* Run only integration tests by calling tests/integration_tests.php 

You can also run one test file at a time by executing the test file directly eg. 
http://path/to/piwik/trunk/tests/core/DataTable.test.php

You can also run tests from the command line:
  $ cd path/to/piwik
  $ find . -name '*.test.php' -exec php {} \;

 UNIT TESTs
===========
Unit tests use the Simpletest Unit Testing framework.
Plugins can also integrate their own unit tests,  in a tests/ directory inside the plugin folder.
Check for example the plugins plugins/SitesManager/tests/ or plugins/UserCountry/tests/

 INTEGRATION TESTS
==================
Integration tests files are in tests/integration/*.test.php

Integration tests allow to test how major Piwik components interact together.
A test will typically generate hits to the Tracker (record visits and page views)
and then test all API responses and for each API output. It then checks that they match expected XML (or CSV, json, etc.).
If a test fails, you can compare the processed/ and expected/ directories in a graphical 
text compare tool, such as WinMerge on Win, to easily view changes between files.
If changes are expected due to the code changes you make, simply copy the file from processed/ to 
expected/, and test will then pass. Otherwise, if you didn't expect to modify the API outputs, 
it might be that your changes are breaking some features unexpectedly.

To run all integration tests at once, execute tests/integration_tests.php
See also http://dev.piwik.org/trac/ticket/1465   

 PHPUNIT TESTS
==============
1) install PHPUnit on your system
	$ cd your/php/directory
	$ sudo pear upgrade PEAR
	$ pear config-set auto_discover 1
	$ pear install --alldeps pear.phpunit.de/PHPUnit
	Doc at: http://www.phpunit.de/manual/current/en/installation.html

2) Configure PHPUnit: Copy the file piwik/tests/PHPUnit/phpunit.xml.dist as phpunit.xml.
	In this file, you will find the following lines.
	Please edit HTTP_HOST and REQUEST_URI to match the hostname and path of the Piwik files:
    <server name="HTTP_HOST" value="localhost"/>
    <server name="REQUEST_URI" value="/path/to/piwik/tests/all_tests.php"/>
	
3) Run the tests
	$ cd /path/to/piwik/tests/PHPUnit
	$ phpunit
	This will run all unit + integration tests. It might take 30 minutes to run.

	You can also run tests of speciified "parts" of Piwik.
	There are three main groups of tests: Core, Plugins and Integration
	For example run
	$ phpunit --group Core
	to run all Core Piwik tests. You may also combine groups like
	$ phpunit --group Core,Plugins

4) Write more tests :)
	See "Writing Unit tests with PHPUnit" 
	http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html

 JAVASCRIPT TESTS
=================
piwik.js is unit tested and you can run tests via piwik/tests/javascript/

 WEBTESTS
=========
The Installation process and few other important tasks are also "webtested". 
These webtests are ran by the continuous integration server Jenkins.
http://dev.piwik.org/svn/trunk/tests/webtest/testcases/

 CONTINOUS INTEGRATION
======================
We currently use Jenkins as continuous integration build server. More information:
http://piwik.org/qa/

 PARTICIPATE
============
You can help by improving existing tests, or identify some missing tests and implement them.
See http://piwik.org/participate/development-process
Please contact us at hello@piwik.org

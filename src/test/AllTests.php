<?php
/**
 * Class to organize all tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  test
 * @version     $Id: AllTests.php 1970 2008-12-19 11:55:04Z mikey $
 */
ini_set('memory_limit', -1);
if (defined('PHPUnit_MAIN_METHOD') === false) {
    define('PHPUnit_MAIN_METHOD', 'src_test_AllTests::main');
}

define('TEST_SRC_PATH', dirname(__FILE__));
require_once TEST_SRC_PATH . '/../../bootstrap.php';
stubBootstrap::init(array('project' => realpath(dirname(__FILE__) . '/../../projects/dist')));
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';
PHPUnit_Util_Filter::addDirectoryToWhitelist(realpath(TEST_SRC_PATH . '/../main/php'));
/**
 * Class to organize all tests.
 *
 * @package     stubforge_image
 * @subpackage  test
 */
class src_test_AllTests extends PHPUnit_Framework_TestSuite
{
    /**
     * runs this test suite
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * returns the test suite to be run
     *
     * @return  PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite   = new self();

        // image
        $suite->addTestFile(TEST_SRC_PATH . '/php/net/stubforge/image/stubImageTestCase.php');
        
        // driver
        $suite->addTestFile(TEST_SRC_PATH . '/php/net/stubforge/image/driver/stubImageDummyDriverTestCase.php');
        $suite->addTestFile(TEST_SRC_PATH . '/php/net/stubforge/image/driver/stubImagePngDriverTestCase.php');

        // response
        $suite->addTestFile(TEST_SRC_PATH . '/php/net/stubforge/image/response/stubDefaultImageResponseTestCase.php');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD === 'src_test_AllTests::main') {
    src_test_AllTests::main();
}
?>
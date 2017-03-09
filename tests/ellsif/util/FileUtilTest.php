<?php
namespace ellsif\util\Test;


use ellsif\util\FileUtil;

class FileUtilTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass()
    {
        FileUtil::makeDirectory(dirname(__FILE__, 2) . 'FileUtilTest');
    }

    public static function tearDownAfterClass()
    {
        unlink(dirname(__FILE__, 2) . 'FileUtilTest');
    }

    public function testWriteFileSuccess()
    {
        $path = dirname(__FILE__, 3) . 'FileUtilTest/testWriteFileSuccess';
        $string = 'test string';
        FileUtil::writeFile($path, $string);

        $this->assertFileExists($path);

        $resultString = file_get_contents($path);
        $this->assertEquals('test string', $resultString);
    }
}
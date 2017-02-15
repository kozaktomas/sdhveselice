<?php

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . "/../../bootstrap.php";

class FileManagerTests extends TestCase
{

    public function setUp()
    {
        if (is_file(__DIR__ . "/test_dir/test.txt")) {
            unlink(__DIR__ . "/test_dir/test.txt");
        }
    }

    public function testGetAllFiles()
    {
        $fileManager = new \App\Model\FileManager(__DIR__ . "/test_dir");
        $files = $fileManager->getAllFiles();

        Assert::equal(4, count($files));
        foreach ($files as $file) {
            Assert::true($file instanceof \SplFileInfo);
        }
    }

    public function testDeleteFile()
    {
        file_put_contents(__DIR__ . "/test_dir/test.txt", "content");
        Assert::true(is_file(__DIR__ . "/test_dir/test.txt"));
        $fileManager = new \App\Model\FileManager(__DIR__ . "/test_dir");
        Assert::true($fileManager->deleteFile("test.txt"));
        Assert::false(is_file(__DIR__ . "/test_dir/test.txt"));
    }

}

$tests = new FileManagerTests();
$tests->run();
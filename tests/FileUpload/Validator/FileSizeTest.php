<?php

namespace FileUpload\Validator;

use FileUpload\File;

class FileSizeTest extends \PHPUnit_Framework_TestCase {
  public function testExceedSize() {
    $validator  = new FileSize(10);
    $file       = new File;
    $file->size = 11;

    $this->assertFalse($validator->validate('', $file, 11));
    $this->assertNotEmpty($file->error);
  }

  public function testExceed1MSize() {
    $validator  = new FileSize("1M");
    $file       = new File;
    $file->size = 1048577;

    $this->assertFalse($validator->validate('', $file, 11));
    $this->assertNotEmpty($file->error);
  }

  /**
   * @expectedException \Exception
   */
  public function testFailMaxSize1A() {
    $validator = new FileSize("1A");
  }

  public function testOk() {
    $validator  = new FileSize(10);
    $file       = new File;
    $file->size = 10;

    $this->assertTrue($validator->validate('', $file, 10));
    $this->assertEmpty($file->error);
  }
}

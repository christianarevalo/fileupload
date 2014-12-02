<?php

namespace FileUpload\Validator;
use FileUpload\File;

class FileTypeTest extends \PHPUnit_Framework_TestCase {
  public function testWrongMime() {
    $validator = new FileType(array('image/png'));
    $file = new File;
    $file->type = 'application/json';

    $this->assertFalse($validator->validate('', $file, 11));
    $this->assertNotEmpty($file->error);
  }

  public function testOk() {
    $validator = new FileType(array('image/png'));
    $file = new File;
    $file->type = 'image/png';

    $this->assertTrue($validator->validate('', $file, 10));
    $this->assertEmpty($file->error);
  }
}

<?php

namespace FileUpload\Validator;

use FileUpload\File;
use FileUpload\Util;

/**
 * Document   : FileSize.php
 * Created on : 30.11.14, 21:49
 * Author     : Christian Arevalo <contact@christianarevalo.ch>
 * Description: File Size Validator
 */
class FileSize implements Validator {
  /**
   * Our errors
   */
  const UPLOAD_ERR_TOO_LARGE = 1;
  /**
   * Max allowed file size
   * @var integer
   */
  protected $max_size;
  /**
   * Error messages
   * @var array
   */
  protected $messages = array(
      self::UPLOAD_ERR_TOO_LARGE => 'file_upload.size.too_large',
  );

  /**
   * @param integer $max_size
   * @param array   $allowed_types
   */
  public function __construct($max_size) {
    $this->setMaxSize($max_size);
  }

  /**
   * Sets the max file size
   *
   * @param mixed $max_size
   *
   * @throws \Exception if the max_size value is invalid
   */
  public function setMaxSize($max_size) {
    if (is_numeric($max_size)) {
      $this->max_size = $max_size;
    } else {
      $this->max_size = Util::humanReadableToBytes($max_size);
    }

    if ($this->max_size < 0 || $this->max_size == null) {
      throw new \Exception('invalid max_size value');
    }
  }

  /**
   * Merge (overwrite) default messages
   *
   * @param array $new_messages
   */
  public function setMessages(array $new_messages) {
    $this->messages = array_merge($this->messages, $new_messages);
  }

  /**
   * @see Validator
   */
  public function validate($tmp_name, File $file, $current_size) {
    if ($file->size > $this->max_size || $current_size > $this->max_size) {
      $file->error = $this->messages[self::UPLOAD_ERR_TOO_LARGE];

      return false;
    }

    return true;
  }
}

/* End of file FileSize.php */
/* Location: ./application/libraries/FileSize.php */
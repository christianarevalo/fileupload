<?php

namespace FileUpload\Validator;

use FileUpload\File;

/**
 * Document   : FileType
 * Created on : 30.11.14, 19:23
 * Author     : Christian Arevalo <contact@christianarevalo.ch>
 * Description: File type validator
 */
class FileType implements Validator {

  /**
   * Our errors
   */
  const UPLOAD_ERR_BAD_TYPE = 0;
  /**
   * Allowed mime types
   * @var array
   */
  protected $allowed_types;
  /**
   * Error messages
   * @var array
   */
  protected $messages = array(
      self::UPLOAD_ERR_BAD_TYPE => 'file_upload.type.not_allowed'
  );

  /**
   * @param array $allowed_types
   */
  public function __construct(array $allowed_types) {
    $this->allowed_types = $allowed_types;
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
    if (!in_array($file->type, $this->allowed_types)) {
      $file->error = $this->messages[self::UPLOAD_ERR_BAD_TYPE];

      return false;
    }

    return true;
  }
}

/* End of file FileType.php */
/* Location: ./application/libraries/FileType.php */
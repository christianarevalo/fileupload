<?php

namespace FileUpload\Validator;

use FileUpload\File;

/**
  * Document   : ImageSize
  * Created on : 01.12.14, 12:47
  * Author     : christianarevalo <contact@christianarevalo.ch>
  * Description: Image size validator
  */
class ImageSize implements Validator {
  /**
   * Our errors
   */
  const IMAGINE_IMAGICK = 'imagick';
  const IMAGINE_GD = 'gd';
  /**
   * Our errors
   */
  const UPLOAD_ERR_MIN_SIZE = 1;
  const UPLOAD_ERR_MAX_SIZE = 2;
  const UPLOAD_ERR_NOT_IMAGE = 3;
  /**
   * Min and/or Max allowed image size
   * @var array
   */
  protected $min_sizes, $max_sizes;
  /**
   * imagine (image factory)
   * @var
   */
  protected $imagine;
  /**
   * Error messages
   * @var array
   */
  protected $messages = array(
      self::UPLOAD_ERR_MIN_SIZE => 'file_upload.image.size.too_small',
      self::UPLOAD_ERR_MAX_SIZE => 'file_upload.image.size.too_large',
      self::UPLOAD_ERR_NOT_IMAGE => 'file_upload.image.wrong_type'
  );

  /**
   * @param array $sizes
   */
  public function __construct($sizes, $imagine_factory = self::IMAGINE_IMAGICK) {
    switch($imagine_factory) {
      case self::IMAGINE_IMAGICK:
        $this->imagine = new \Imagine\Imagick\Imagine();
        break;
      case self::IMAGINE_GD:
        $this->imagine = new \Imagine\GD\Imagine();
        break;
    }
    $this->min_sizes = null;
    $this->max_sizes = null;
    $this->setSizes($sizes);
  }

  /**
   * Sets the max file size
   *
   * @param mixed $max_size
   *
   * @throws \Exception if the max_size value is invalid
   */
  public function setSizes($sizes) {
    if ( is_array($sizes) && (array_key_exists('min_sizes', $sizes) || array_key_exists('max_sizes', $sizes)) ) {

      if ( $this->checkSize('min_sizes', $sizes) ) {
        $this->min_sizes = $sizes['min_sizes'];
      }

      if ( $this->checkSize('max_sizes', $sizes) ) {
        $this->max_sizes = $sizes['max_sizes'];
      }

    } else {
      throw new \Exception('invalid sizes value');
    }
  }

  protected function checkSize($size, $array) {
    if ( array_key_exists($size, $array) ) {
      $values = $array[$size];
      if ( array_key_exists('width', $values) || array_key_exists('height', $values) ) {
        $this->checkValue('width', $this->min_sizes);
        $this->checkValue('height', $this->min_sizes);
      } else {
        throw new \Exception('invalid min_size arguments');
      }
      return true;
    }

    return false;
  }

  protected function checkValue($key, $array) {
    if ( array_key_exists($key, $array) ) {
      $value = $array[$key];
      if ( ! is_numeric($value) || $value < 0 ) {
        throw new \Exception('invalid ' . $key . ' value');
      }
    }

    return true;
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
    try {
      $image = $this->imagine->open($file->path);
      $size = $image->getSize();
      $width = $size->getWidth();
      $height = $size->getHeight();

      if ( ! $this->validateMinSize($width, $height) ) {
        $file->error = $this->messages[self::UPLOAD_ERR_MIN_SIZE];
        return false;
      }

      if ( ! $this->validateMaxSize($width, $height) ) {
        $file->error = $this->messages[self::UPLOAD_ERR_MAX_SIZE];
        return false;
      }

    } catch (\Imagine\Exception\Exception $e) {
      $file->error = $this->messages[self::UPLOAD_ERR_NOT_IMAGE];
      return false;
    }

    return true;
  }

  protected function validateMinSize($width, $height) {

    if ( ! is_null($this->min_sizes) ) {
      if ( array_key_exists('width', $this->min_sizes) ) {
        if ( $width < $this->min_sizes['width'] ) {
          return false;
        }
      }

      if ( array_key_exists('height', $this->min_sizes) ) {
        if ( $height < $this->min_sizes['height'] ) {
          return false;
        }
      }
    }

    return true;
  }

  protected function validateMaxSize($width, $height) {

    if ( ! is_null($this->max_sizes) ) {
      if ( array_key_exists('width', $this->max_sizes) ) {
        if ( $width > $this->max_sizes['width'] ) {
          return false;
        }
      }

      if ( array_key_exists('height', $this->max_sizes) ) {
        if ( $height > $this->max_sizes['height'] ) {
          return false;
        }
      }
    }

    return true;
  }
}

/* End of file ImageSize.php */
/* Location: ./application/libraries/ImageSize.php */
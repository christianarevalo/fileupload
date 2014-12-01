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
  protected $min_size, $max_size;
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
    $this->min_size = null;
    $this->max_size = null;
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
    if ( is_array($sizes) && (array_key_exists('min_size', $sizes) || array_key_exists('max_size', $sizes)) ) {

      if ( $this->checkSize('min_size', $sizes) ) {
        $this->min_size = $sizes['min_size'];
      }

      if ( $this->checkSize('max_size', $sizes) ) {
        $this->max_size = $sizes['max_size'];
      }

    } else {
      throw new \Exception('invalid sizes value');
    }
  }

  protected function checkSize($size, $array) {
    if ( array_key_exists($size, $array) ) {
      $values = $array[$size];
      if ( array_key_exists('width', $values) || array_key_exists('height', $values) ) {
        $this->checkValue('width', $values);
        $this->checkValue('height', $values);
      } else {
        throw new \Exception('invalid ' . $size . ' arguments');
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
      $image = $this->imagine->open($tmp_name);
      $size = $image->getSize();
      $width = $size->getWidth();
      $height = $size->getHeight();
      $file->width = $width;
      $file->height = $height;

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

    if ( ! is_null($this->min_size) ) {
      if ( array_key_exists('width', $this->min_size) ) {
        if ( $width < $this->min_size['width'] ) {
          return false;
        }
      }

      if ( array_key_exists('height', $this->min_size) ) {
        if ( $height < $this->min_size['height'] ) {
          return false;
        }
      }
    }

    return true;
  }

  protected function validateMaxSize($width, $height) {

    if ( ! is_null($this->max_size) ) {
      if ( array_key_exists('width', $this->max_size) ) {
        if ( $width > $this->max_size['width'] ) {
          return false;
        }
      }

      if ( array_key_exists('height', $this->max_size) ) {
        if ( $height > $this->max_size['height'] ) {
          return false;
        }
      }
    }

    return true;
  }
}

/* End of file ImageSize.php */
/* Location: ./application/libraries/ImageSize.php */
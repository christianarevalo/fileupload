<?php

namespace FileUpload\FileSystem;

class Mock implements FileSystem {
  /**
   * @see FileSystem
   */
  public function isFile($path) {
    return is_file($path);
  }

  /**
   * @see FileSystem
   */
  public function isDir($path) {
    return is_dir($path);
  }
  
  /**
   * @see FileSystem
   */
  public function fileExists($path) {
    return file_exists($path);
  }

  /**
   * @see FileSystem
   */
  public function mkDir($path) {
    return mkdir($path, 0700, true);
  }

  /**
   * @see FileSystem
   */
  public function rmDir($path) {
	  return rmdir($path);
  }

  /**
   * @see FileSystem
   */
  public function isUploadedFile($path) {
    return true;
  }

  /**
   * @see FileSystem
   */
  public function moveUploadedFile($from_path, $to_path) {
    return rename($from_path, $to_path);
  }

  /**
   * @see FileSystem
   */
  public function writeToFile($path, $stream, $append = false) {
    return file_put_contents($path, $stream, $append ? \FILE_APPEND : 0);
  }

  /**
   * @see FileSystem
   */
  public function getInputStream() {
    return fopen('php://input', 'r');
  }

  /**
   * @see FileSystem
   */
  public function getFileStream($path) {
    return fopen($path, 'r');
  }

  /**
   * @see FileSystem
   */
  public function unlink($path) {
    return unlink($path);
  }

  /**
   * @see FileSystem
   */
  public function clearStatCache($path) {
    return clearstatcache(true, $path);
  }

  /**
   * @see FileSystem
   */
  public function getFilesize($path) {
    return filesize($path);
  }

  /**
   * @see FileSystem
   */
  public function getFileInfo($path) {
    return pathinfo($path);
  }
  
  /**
   * @see FileSystem
   */
  public function realPath($relative_path) {
    return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $relative_path;
  }
}

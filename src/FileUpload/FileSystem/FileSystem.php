<?php

namespace FileUpload\FileSystem;

interface FileSystem {
  /**
   * Is it a file?
   * @param  string  $path
   * @return boolean
   */
  public function isFile($path);

  /**
   * Is it a directory?
   * @param  string  $path
   * @return boolean
   */
  public function isDir($path);

  /**
   * file exists?
   * @param  string  $path
   * @return boolean
   */
  public function fileExists($path);
  
  /**
   * Creates a directory
   * @param  string  $path
   * @return boolean
   */
  public function mkDir($path);
  
  /**
   * Removes a directory
   * @param  string  $path
   * @return boolean
   */
  public function rmDir($path);
  
  /**
   * Is it a previously uploaded file?
   * @param  string  $path
   * @return boolean
   */
  public function isUploadedFile($path);

  /**
   * Move file
   * @param  string  $from_path
   * @param  string  $to_path
   * @return boolean
   */
  public function moveUploadedFile($from_path, $to_path);

  /**
   * Write file or append to file
   * @param  string   $path
   * @param  resource $stream
   * @param  boolean  $append
   * @return boolean
   */
  public function writeToFile($path, $stream, $append = false);

  /**
   * Get file stream from PHP input
   * @return resource
   */
  public function getInputStream();

  /**
   * Get file stream from file
   * @return resource
   */
  public function getFileStream($path);

  /**
   * Delete path
   * @param  string  $path
   * @return boolean
   */
  public function unlink($path);

  /**
   * Clear filesize cache on disk
   * @param  string  $path
   * @return boolean
   */
  public function clearStatCache($path);

  /**
   * Get file size
   * @param  string  $path
   * @return integer
   */
  public function getFilesize($path);

  /**
   * Get file size
   * @param  string  $path
   * @return integer
   */
  public function getFileInfo($path);

  /**
   * Get real path
   * @param  string  $relative_path
   * @return string
   */
  public function realPath($relative_path);
}

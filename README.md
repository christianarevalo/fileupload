FileUpload
==========

[![Build Status](https://travis-ci.org/Gargron/fileupload.png?branch=master)](https://travis-ci.org/Gargron/fileupload)

PHP FileUpload library that supports chunked uploads. Adopted from the
procedural script included with [jQuery-File-Upload][1], designed to work
with that JavaScript plugin, with normal forms, and to be embeddable into
any application/architecture.

[1]: https://github.com/blueimp/jQuery-File-Upload

### Project forked

This project was forked from Gargron/fileupload and customized to integrate in my CodeIgniter Projects.

### Installing

This package is available via Composer:

```json
{
  "repositories":
      [
        {
          "type": "git",
          "url": "https://github.com/christianarevalo/fileupload.git"
        }
      ],
  "require": {
    "christianarevalo/fileupload": "1.0.*"
  }
}
```

### Status

The unit test suite covers simple uploads, but needs to be updated using the new validators.
TODO: ImageSizeTest, mkDir in FileSystem

### Usage

```php
// Size validation (max file size 2MB and only two allowed mime types)
$validator = new FileUpload\Validator\FileSize(1024 * 1024 * 2);

// Type validation (only two allowed mime types)
$validator = new FileUpload\Validator\FileType(['image/png', 'image/jpg']);

// Image size validation (some examples of sizes, you can combine both max_size and min_size)
$sizes_1 = array('max_size' => array( 'width' => 500 ) );
$sizes_2 = array('max_size' => array( 'width' => 500, 'height' => 800 ) );
$sizes_3 = array('min_size' => array( 'height' => 250 ) );
$sizes_4 = array('min_size' => array( 'width' => 300, 'height' => 300 ) );

// By default it uses imagick (ImageMagick) but you can use GD
$validator = new FileUpload\Validator\ImageSize($sizes_1);

// Simple path resolver, where uploads will be put
$pathresolver = new FileUpload\PathResolver\Simple('/my/uploads/dir');

// The machine's filesystem
$filesystem = new FileUpload\FileSystem\Simple();

// FileUploader itself
$fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);

// Adding it all together. Note that you can use multiple validators or none at all
$fileupload->setPathResolver($pathresolver);
$fileupload->setFileSystem($filesystem);
$fileupload->addValidator($validator);

// Doing the deed
list($files, $headers) = $fileupload->processAll();

// Outputting it, for example like this
foreach($headers as $header => $value) {
  header($header . ': ' . $value);
}

echo json_encode(array('files' => $files));
```

### Validator

If you want you can use the common human readable format for filesizes like "1M", "1G", just pass the String as the first Argument.

```
$validator = new FileUpload\Validator\Simple("10M", ['image/png', 'image/jpg']);
$validator = new FileUpload\Validator\FileSize("10M");
```

Here is a listing of the possible values (B => B; KB => K; MB => M; GB => G). These values are Binary convention so basing on 1024.

### FileNameGenerator  

With the FileNameGenerator you have the possibility to change under witch Filename we uploaded files will be saved. 

``` 
$fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
$filenamegenerator = new FileUpload\FileNameGenerator\Simple();
$fileupload->setFileNameGenerator($filenamegenerator);
```

We have placed some example generators like md5 who saves the file under the md5 hash of the filename or the random generator witch uses an random string. The default (the simple generator to be more precise) will save the file by its origin name.

### Callbacks

Currently implemented events:

* `completed`

```php
$fileupload->addCallback('completed', function(FileUpload\File $file) {
  // Whoosh!
});
```

### I18n

For internationalization purposes, this library uses formatted keys for all error messages, i.e. 'file_upload.size.too_large'.
```
/*
 * FileUpload errors
 */
$lang['file_upload.error.ini_size'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$lang['file_upload.error.form_size'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$lang['file_upload.error.partial'] = 'The uploaded file was only partially uploaded';
$lang['file_upload.error.no_file'] = 'No file was uploaded';
$lang['file_upload.error.no_temp_dir'] = 'Missing a temporary folder';
$lang['file_upload.error.write'] = 'Failed to write file to disk';
$lang['file_upload.error.php_extension'] = 'A PHP extension stopped the file upload';
$lang['file_upload.error.php_size'] = 'The upload file exceeds the post_max_size or the upload_max_filesize directives in php.ini';

/*
 * FileUpload validator
 */
$lang['file_upload.type.not_allowed'] = 'File type not allowed';
$lang['file_upload.size.too_large'] = 'File size too large';
$lang['file_upload.image.size.too_small'] = 'Image too small';
$lang['file_upload.image.size.too_large'] = 'Image too large';
$lang['file_upload.image.wrong_type'] = 'Not an image';
```

### License

Licensed under the MIT license, see `LICENSE` file.

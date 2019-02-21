[![Say Thanks!](https://img.shields.io/badge/Say%20Thanks-!-1EAEDB.svg)](https://saythanks.io/to/a-sabagh)
# simple-file-uploader
🐘 Simple and Secure PHP File Uploader with greate options and very light and easy to use.
## Requirement
* php 5.6 >=
## Install Package
to install simple-file-uploader in your php project you can using composer
```
composer require a-sabagh/simple-file-uploader
```
if you never willing to using composer you can download from <a href="https://github.com/a-sabagh/simple-file-uploader" title="simple-file-uploader">Package</a> and require `src/Uploader.php`
## Usage
You can access to faker objects by according blew following table:

```php
use SimpleUploader\Uploader;
$handle = new Uploader(__DIR__ . "/uploads");
```

`Uploader` class just accept one parameter as destination of uploader files

| Method | Parameter | Return | Descriptions |
| --- | --- | -- | -- |
| ``` $handle->setType() ``` | Array | Void | Set acceptable file type for Uploading |
| ``` $handle->setMaxSize() ``` | Integer | Void | Set max size for file in Byte |
| ``` $handle->getMessages() ``` | NULL | Array | Get result message of uploading procccess |
| ``` $handle->upload() ``` | `$_FILE['file_name']` | Array | Upload files and return uploading files info |

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

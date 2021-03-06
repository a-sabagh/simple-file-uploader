[![Latest Stable Version](https://poser.pugx.org/a-sabagh/simple-file-uploader/v/stable)](https://packagist.org/packages/a-sabagh/simple-file-uploader)
[![Total Downloads](https://poser.pugx.org/a-sabagh/simple-file-uploader/downloads)](https://packagist.org/packages/a-sabagh/simple-file-uploader)
[![License](https://poser.pugx.org/a-sabagh/simple-file-uploader/license)](https://packagist.org/packages/a-sabagh/simple-file-uploader)
[![composer.lock](https://poser.pugx.org/a-sabagh/simple-file-uploader/composerlock)](https://packagist.org/packages/a-sabagh/simple-file-uploader)
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
if you never willing to using composer you can download from [Package](https://github.com/a-sabagh/simple-file-uploader) and require `src/Uploader.php` and use namespace
## Usage
first,use name space and create object from `Uploader` class

```php
use SimpleUploader\Uploader;
$handle = new Uploader(__DIR__ . "/uploads");
```

`Uploader` class just accept one parameter as a destination of uploader files

| Method | Parameter | Return | Descriptions |
| --- | --- | -- | -- |
| ``` $handle->setType() ``` | Array | Void | Set acceptable file type for Uploading |
| ``` $handle->setMaxSize() ``` | Integer | Void | Set max size for file in Byte |
| ``` $handle->getMessages() ``` | NULL | Array | Get result message of uploading proccess |
| ``` $handle->upload() ``` | `$_FILE['file_name']` | Array | Upload files and return uploading files info |

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

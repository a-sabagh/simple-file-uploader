<?php
require __DIR__ . '/vendor/autoload.php';

use SimpleUploader\Uploader;

if (isset($_POST['submit'])) {
    $max_size = 200 * 1024;
    try {
        $attachment = new Uploader(__DIR__ . "/uploads");
        $attachment->setMaxSize($max_size);
        $attachment->setType(array("image/jpeg", "image/png", "image/webp", "image/x-icon", "application/zip", "application/pdf", "application/x-rar-compressed"));
        $result = $attachment->upload($_FILES['file_name']);
        $messages = $attachment->getMessages();
    } catch (Exception $e) {
        $exception_error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Uploader</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>upload center:</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file_name[]" multiple>
            <input type="submit" name="submit" value="upload">
        </form>
    </body>
</html>

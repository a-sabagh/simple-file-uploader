<?php
require __DIR__ . '/vendor/autoload.php';

use SimpleUploader\Uploader;

if (isset($_POST['submit'])) {
    $max_size = 200 * 1024;
    try {
        $destination = __DIR__ . "/uploads";
        $attachment = new Uploader($destination);
        $attachment->setMaxSize($max_size);
        $attachment->setType(array("text/x-python", "image/jpeg", "image/png", "image/webp", "image/x-icon", "application/zip", "application/pdf", "application/x-rar-compressed"));
    } catch (Exception $e) {
        $exception_error = $e->getMessage();
    }
    $attachment->upload($_FILES['file_name']);
    $messages = $attachment->getMessages();
    if (isset($exception_error)) {
        echo $exception_error;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>upload center</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>upload center:</h1>
        <pre><?php (isset($_FILES['file_name']))? print_r($_FILES['file_name']): ''; ?></pre>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file_name[]" multiple>
            <input type="submit" name="submit" value="upload">
        </form>
    </body>
</html>
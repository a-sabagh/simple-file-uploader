<?php
namespace SimpleUploader;

/*
 * Set error reporting to the level to which Es code must comply.
 */
 
error_reporting(E_ALL | E_STRICT);

/**
 * Overwrite here php functions
 */

function is_uploaded_file($filepath){
    return file_exists($filepath);
}

function move_uploaded_file($filepath, $destination){
    return copy($filepath, $destination);
}

/*
 * load Uploader Class for testing
 */
 
require __DIR__ . '/../src/Uploader.php';

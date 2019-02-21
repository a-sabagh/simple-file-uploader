<?php

namespace SimpleUploader;

use Exception;

class Uploader {

    protected $destination;
    protected $startPath;
    protected $messages = array();
    protected $uploadOk = TRUE;
    protected $fileName = NULL;
    protected $fileSize = 1024 * 1024;
    protected $fileType = array("image/jpeg", "image/png", "image/webp", "image/x-icon", "application/zip", "application/pdf", "application/x-rar-compressed");
    protected $blacklistExt = array("js", "py", "exe", "php", "dmg", "php3", "php4", "phtml", "pl", "jsp", "asp", "htm", "shtml", "sh", "cgi");
    protected $suffix = ".txt";

    /**
     * create destination with this template '$folder/year/month/'
     * @param type $destination
     * @throws Exception
     */
    function __construct($destination) {
        if (is_dir($destination) && is_writable($destination)) {
            $this->makeDestination($destination);
        } else {
            throw new Exception("{$destination} must be real directory and be writable");
        }
    }

    /**
     * make directory for destination using year and mounth
     * @param type $destination
     */
    protected function makeDestination($destination) {
        $this->startPath = $destination;
        $perma_path = "";
        $year = date("Y");
        $month = date("m");
        if ($destination[strlen($destination) - 1] !== "/") {
            $perma_path = "{$destination}/{$year}/{$month}/";
        } else {
            $perma_path = "{$destination}{$year}/{$month}/";
        }
        if (!is_dir($perma_path)) {
            mkdir($perma_path, 0755, TRUE);
        }
        $this->destination = $perma_path;
    }

    /**
     * uploading proccess for multiple and singular uploading file
     * @param type $file
     */
    public function upload($file) {
        $output = array();
        if (is_array($file['name'])) {
            $current_file = array();
            $count = count(current($file));
            for ($i = 0; $i < $count; $i++) {
                $current['name'] = $file['name'][$i];
                $current['type'] = $file['type'][$i];
                $current['tmp_name'] = $file['tmp_name'][$i];
                $current['error'] = $file['error'][$i];
                $current['size'] = $file['size'][$i];
                $this->fileName = $this->checkName($current['name']);
                $this->uploadOk = TRUE;
                $this->checkFile($current, $i);
                if ($this->uploadOk) {
                    $result = $this->moveFileUpload($current, $i);
                    $output[] = $this->prepareOutput($result);
                }//uploadOk
            }//for loop
        } else {
            $this->fileName = $this->checkName($file['name']);
            $this->checkFile($file);
            if ($this->uploadOk) {
                $result = $this->moveFileUpload($file);
                $output[] = $this->prepareOutput($result);
            }//uploadOk
        }//is array
        return $output;
    }

    /**
     * Prepare file information to return by upload method
     */
    protected function prepareOutput($result) {
        if ($result) {
            $pathInfo = pathinfo($this->fileName);
            $output = array(
                'filename' => $pathInfo['filename'],
                'basename' => $pathInfo['basename'],
                'path' => str_replace($this->startPath, "", $this->destination) . $this->fileName,
                'extension' => $pathInfo['extension'],
                'size' => filesize($this->destination . $this->fileName)
            );
        } else {
            $output = false;
        }
        return $output;
    }

    /**
     * checking size and type and error of the uploading file
     * @param type $file
     */
    protected function checkFile($file, $i = 0) {
        if (!$this->checkSize($file['size'], $i)) {
            $this->uploadOk = FALSE;
        }
        if (!$this->checkType($file['type'], $i)) {
            $this->uploadOk = FALSE;
        }
        if (!$this->checkError($file['error'], $i)) {
            $this->uploadOk = FALSE;
        }
    }

    /**
     * convert string value to byte for example 1Mb = 1024 byte
     * @param type $string
     * @return boolean|int
     */
    protected static function convertToByte($string) {
        $output = (int) $string;
        $unit = strtolower($string[strlen($string) - 1]);
        $computer_units = array("k", "m", "g");
        if (in_array($unit, $computer_units)) {
            switch ($unit) {
                case "g":
                    $output *= 1024;
                case "m":
                    $output *= 1024;
                case "k":
                    $output *= 1024;
            }
            return $output;
        } else {
            return FALSE;
        }
    }

    /**
     * seting max size for uploading file and check it with ini server max size
     * @param type $size
     * @throws Exception
     */
    public function setMaxSize($size) {
        $serverMaxSize = self::convertToByte(ini_get("upload_max_filesize"));
        $size = (int) $size;
        $serverMaxSize = (int) $serverMaxSize;
        if ($size > $serverMaxSize) {
            throw new Exception("{$size} is greater than server maximum file size");
        }
        if (is_numeric($size) && !empty($size) && $size !== 0) {
            $this->fileSize = $size;
        }
    }

    /**
     * checking size of uploading file 
     * @param type $size
     * @return boolean
     */
    protected function checkSize($size,$i=0) {
        if ($size > $this->fileSize) {
            $this->messages[$i]['error'][] = $this->fileName . " is to big";
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * set type for uploading file
     * @param type $array_type
     * @throws Exception
     */
    public function setType($array_type) {
        if (is_array($array_type)) {
            $this->fileType = $array_type;
        } else {
            throw new Exception("uploadCenter::setType parameter must be array");
        }
    }

    /**
     * checking type of uploading file 
     * @param type $type
     * @return boolean
     */
    protected function checkType($type, $i = 0) {
        if (in_array($type, $this->fileType)) {
            return TRUE;
        } else {
            $this->messages[$i]['error'][] = "type of {$type} file is illegal";
            return FALSE;
        }
    }

    /**
     * checking error of uploading file
     * @param type $error
     * @return boolean
     */
    protected function checkError($error, $i=0) {
        switch ($error) {
            case 0:
                return TRUE;
            case 1;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_INI_SIZE';
                return FALSE;
            case 2;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_FORM_SIZE';
                return FALSE;
            case 3;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_PARTIAL';
                return FALSE;
            case 4;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_NO_FILE';
                return FALSE;
            case 6;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_NO_TMP_DIR';
                return FALSE;
            case 7;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_CANT_WRITE';
                return FALSE;
            case 8;
                $this->messages[$i]['error'][] = 'UPLOAD_ERR_EXTENSION';
                return FALSE;
        }
    }

    /**
     * checking name of uploading file for rename file that exist in folder and replace space with  underscore
     * and neutrilize blacklist extencion
     * @param type $name
     * @return string $name
     */
    protected function checkName($name) {
        if (strpos($name, " ")) {
            $name = str_replace(" ", "_", $name);
        }

        $pathinfo = pathinfo($name);
        $extension = $pathinfo['extension'];
        $filename = $pathinfo['filename'];
        if (in_array($extension, $this->blacklistExt)) {
            $name = $name . $this->suffix;
        }
        $existing_files = scandir($this->destination);
        $i = 1;
        while (in_array($name, $existing_files)) {
            $name = $filename . "_{$i}." . $extension;
            $i++;
        }
        return $name;
    }

    /**
     * moving uploaded file from temp directory to permanenet path
     * finally showing message if file rename 
     * @param type $file
     */
    protected function moveFileUpload($file, $i = 0) {

        $temp_path = $file['tmp_name'];
        $destination = $this->destination;
        $destination = $destination . $this->fileName;
        $result = move_uploaded_file($temp_path, $destination);
        if ($result) {
            if ($file['name'] !== $this->fileName) {
                $this->messages[$i]['rename'] = "{$file['name']} is rename to " . $this->fileName;
            }
            $this->messages[$i]['success'] = $this->fileName . " was uploaded successfully";
            return true;
        } else {
            $this->messages[$i]['error'][] = "uploading " . $this->fileName . " fail";
            return false;
        }
    }

    /**
     * show array messages
     * @return type
     */
    public function getMessages() {
        return $this->messages;
    }

}

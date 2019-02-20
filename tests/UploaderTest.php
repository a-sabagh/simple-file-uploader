<?php

use PHPUnit\Framework\TestCase;
use SimpleUploader\Uploader;

class Example extends TestCase {

    private $destination = __DIR__ . "/uploads";
    protected $handle;

    public function setUp() {

        $this->handle = new Uploader($this->destination);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    private function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * is directory empty or not
     *
     * @param string destination
     * @return boolean
     */
    private function isEmptyDir($destination) {
        if (!is_readable($destination))
            return null;
        $dircontent = scandir($destination);
        if (count($dircontent) == 2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * remove empty directory recursivly
     *
     * @param string destination
     * @return void
     */
    private function recursiveRemoveDir($destination) {
        if ($this->isEmptyDir($destination)) {
            rmdir($destination);
        } else {
            $destinationScanned = scandir($destination);
            $count = count($destinationScanned);
            for ($i = 0; $i < $count; $i++) {
                $file = $destinationScanned[$i];
                if ($file !== '.' && $file !== '..') {
                    $this->recursiveRemoveDir("{$destination}/{$file}");
                }
            }//endwhile
            rmdir($destination);
        }//directory not empty
    }

    /**
     * upload method test
     * @dataProvider UploadProvider
     * @test
     */
    public function upload($file) {
        $result = $this->handle->upload($file);
        $messages = $this->handle->getMessages();
        for ($i = 0; $i < count($result); $i++):
            $this->assertArrayHasKey('success', $messages[$i]);
            unlink($this->destination . $result[$i]['path']);
        endfor;
    }

    /**
     * provider for upload test
     */
    public function UploadProvider() {
        $file = [
            'name' => 'image.png',
            'type' => 'image/png',
            'tmp_name' => __DIR__ . "/tmp/image.png",
            'error' => 0,
            'size' => 98174
        ];
        $singleFile = $file;
        $multiFile = [
            'name' => [
                $file['name'],
                $file['name']
            ],
            'type' => [
                $file['type'],
                $file['type']
            ],
            'tmp_name' => [
                $file['tmp_name'],
                $file['tmp_name']
            ],
            'error' => [
                $file['error'],
                $file['error']
            ],
            'size' => [
                $file['size'],
                $file['size']
            ]
        ];
        return [
            [$singleFile],
            [$multiFile]
        ];
    }

    /**
     * test if directory is created
     * @dataProvider directoryCreatedProvider
     * @test
     */
    public function directoryCreated($destination, $year, $month) {
        $this->invokeMethod($this->handle, "makeDestination", [$destination]);
        if ($destination[strlen($destination) - 1] !== "/") {
            $perma_path = "{$destination}/{$year}/{$month}/";
        } else {
            $perma_path = "{$destination}{$year}/{$month}/";
        }
        $this->assertTrue(is_dir($perma_path));
        $this->recursiveRemoveDir($destination);
    }

    /**
     * test if directory is created
     * @dataProvider directoryCreatedProvider
     */
    public function directoryCreatedProvider() {
        $year = date("Y");
        $month = date("m");
        return [
            [__DIR__ . "/upload-test", $year, $month],
            [__DIR__ . "/upload-test/", $year, $month]
        ];
    }

    /**
     * check filename
     * @dataProvider checkNameProvider
     * @test
     */
    public function checkName($input, $output) {
        $result = $this->invokeMethod($this->handle, "checkName", [$input]);
        $this->assertEquals($result,$output);
    }

    /**
     * provider
     */
    public function checkNameProvider() {
        return [
            ["index.py","index.py.txt"],
            ["inde x.png","inde_x.png"],
        ];
    }
    
    /**
     * check file size
     * @test
     */
    public function checkSize() {
        $this->handle->setMaxSize(5 * 1024);
        $result = $this->invokeMethod($this->handle, "checkSize",[6 * 1024]);
        $this->assertFalse($result);
    }

    /**
     * check file type
     * @test
     */
    public function checkType(){
        $this->handle->setType(['image/png']);
        $result = $this->invokeMethod($this->handle, "checkType",["image/png"]);
        $this->assertTrue($result);
    }
}

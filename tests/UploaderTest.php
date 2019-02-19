<?php

use PHPUnit\Framework\TestCase;
use SimpleUploader\Uploader;

class Example extends TestCase
{
	private $file;
	private $destination = __DIR__ . "/uploads";
	protected $handle;
	
	public function setUp()
	{
		$this->file = [
		    'name' => 'image.png',
            'type' => 'image/png',
            'tmp_name' => __DIR__ . "/tmp/image.png",
            'error' => 0,
            'size' => 98174
		];
		$this->handle = new Uploader($this->destination);
	}
    /**
	 * test single upload file
	 * @test
	 */
    public function singleUpload()
	{
		$result = $this->handle->upload($this->file);
		$messages = $this->handle->getMessages();
		for($i=0;$i<count($result);$i++):
			$this->assertArrayHasKey('success',$messages[$i]);
			unlink($this->destination . $result[$i]['path']);
		endfor;
    }
    /**
	 * test multiple upload file
	 * @test
	 */
	public function multipleUpload()
	{
		$multiFile = [
			'name' => [
				$this->file['name'],
				$this->file['name']
			],
			'type' => [
				$this->file['type'],
				$this->file['type']
			],
			'tmp_name' => [
				$this->file['tmp_name'],
				$this->file['tmp_name']
			],
			'error' => [
				$this->file['error'],
				$this->file['error']
			],
			'size' => [
				$this->file['size'],
				$this->file['size']
			]
		];
		$result = $this->handle->upload($multiFile);
		$messages = $this->handle->getMessages();
		for($i=0;$i<count($result);$i++):
			$this->assertArrayHasKey('success',$messages[$i]);
			unlink($this->destination . $result[$i]['path']);
		endfor;
	}
}
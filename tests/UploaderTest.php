<?php

use PHPUnit\Framework\TestCase;
use SimpleUploader\Uploader;

class Example extends TestCase
{
	private $destination = __DIR__ . "/uploads";
	protected $handle;
	
	public function setUp()
	{
		$this->handle = new Uploader($this->destination);
	}
    /**
	 * upload method test
	 * @dataProvider UploadProvider
	 * @test
	 */
    public function upload($file)
	{
		$result = $this->handle->upload($file);
		$messages = $this->handle->getMessages();
		for($i=0;$i<count($result);$i++):
			$this->assertArrayHasKey('success',$messages[$i]);
			unlink($this->destination . $result[$i]['path']);
		endfor;
    }
    /**
	 * provider for upload test
	 */
	public function UploadProvider()
	{
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
}
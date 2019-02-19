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
            'tmp_name' => __DIR__ . "tmp/image.png",
            'error' => 0,
            'size' => 98174
		];
		$this->handle = new Uploader($this->destination);
	}
    /** @test */
    public function upload()
	{
		$result = $this->handle->upload($this->file);
		var_dump($this->handle->getMessages());
		$this->assertTrue(true);
    }
}
<?php

class ImageProcessingService
{
	private $__sourceFile;

	/**
	 * @param $sourceFile
	 */
	public function __construct($sourceFile)
	{
		$this->__sourceFile = $sourceFile;
	}

	/**
	 * @param $thumbnailPath
	 * @param $width
	 * @param $height
	 *
	 * @return bool
	 */
	public function createThumbnail($thumbnailPath, $width, $height = \false)
	{
		$result = false;

		$phpThumb = new PhpThumb_PhpThumb();
		$phpThumb->setSourceData(file_get_contents($this->__sourceFile));

		$phpThumb->setParameter('w', $width);
		if ($height !== \false)
		{
			$phpThumb->setParameter('h', $height);
		}

		if ($phpThumb->GenerateThumbnail()) {
			$phpThumb->RenderToFile($thumbnailPath);
			$result = true;
		}

		$phpThumb->purgeTempFiles();

		return $result;
	}

	/**
	 * @param $sourceFile
	 */
	public function setSourceFile($sourceFile)
	{
		$this->__sourceFile = $sourceFile;
	}
}
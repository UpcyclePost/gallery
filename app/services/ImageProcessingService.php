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
	 *
	 * @return bool
	 */
	public function createThumbnail($thumbnailPath, $width)
	{
		$result = false;

		$phpThumb = new PhpThumb_PhpThumb();
		$phpThumb->setSourceData(file_get_contents($this->__sourceFile));

		$phpThumb->setParameter('w', $width);

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
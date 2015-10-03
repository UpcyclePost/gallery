<?php

class ImageProcessingService
{
	private $__sourceFile;

	/**
	 * @param $sourceFile
	 */
	public
	function __construct($sourceFile)
	{
		$this->__sourceFile = $sourceFile;
	}

	/**
	 * @param $thumbnailPath
	 * @param $width
	 * @param $height
	 * @param $crop
	 *
	 * @return bool
	 */
	public
	function createThumbnail($thumbnailPath, $width, $height = \false, $crop = \false)
	{
		$result = false;

		if (!$crop || $height === \false)
		{
			$phpThumb = new PhpThumb_PhpThumb();
			$phpThumb->setSourceData(file_get_contents($this->__sourceFile));
			$phpThumb->setParameter('f', 'png');
			$phpThumb->setParameter('far', 'C');

			$phpThumb->setParameter('w', $width);
			if ($height !== \false)
			{
				$phpThumb->setParameter('h', $height);
			}

			if ($crop)
			{
				$phpThumb->setParameter('far', 'C');
				$phpThumb->setParameter('zc', 'C');
			}

			if ($phpThumb->GenerateThumbnail())
			{
				$phpThumb->RenderToFile($thumbnailPath);
				$result = true;
			}

			$phpThumb->purgeTempFiles();
		}
		else
		{
			// If we're cropping the image, use stoj/crop

			$cropper = new \stojg\crop\CropBalanced($this->__sourceFile);
			/**
			 * @var \Imagick
			 */
			if ($croppedImage = $cropper->resizeAndCrop($width, $height))
			{
				$croppedImage->setImageFormat('png');
				$croppedImage->writeImage($thumbnailPath);
				$result = \true;
			}
		}

		return $result;
	}

	/**
	 * @param $sourceFile
	 */
	public
	function setSourceFile($sourceFile)
	{
		$this->__sourceFile = $sourceFile;
	}
}
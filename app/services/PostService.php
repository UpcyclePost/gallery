<?php

class PostService
{
	protected $config;

	public function __construct()
	{
		$this->config = Phalcon\DI::getDefault()->get('config');
	}

	/**
	 * Creates thumbnails for uploaded images.
	 *
	 * @param $file
	 * @param $postIk
	 * @param $postId
	 *
	 * @return bool
	 */
	public function createThumbnails(&$file, $postIk, $postId)
	{
		$permanentFile = $this->config->application->uploadDir . $postId . '.png';
		$thumbnailLarge = $this->config->application->thumbnailDir . $postId . '-' . $postIk . '.big.png';
		$thumbnailSmall = $this->config->application->thumbnailDir . $postId . '-' . $postIk . '.small.png';

		// Move the uploaded file
		if ($file->moveTo($permanentFile))
		{
			// Create small and large thumbnails
			list($width, $height, $type, $attr) = @getimagesize($permanentFile);

			// Initialize the Image Processing Service
			$imageProcessingService = new ImageProcessingService($permanentFile);
			// Create large thumbnail
			$largeResult = $imageProcessingService->createThumbnail($thumbnailLarge, ($width >= 560) ? 560 : $width);
			$smallResult = $imageProcessingService->createThumbnail($thumbnailSmall, ($width >= 244) ? 244 : $width);

			unlink($permanentFile);

			return ($largeResult && $smallResult);
		}

		return \false;
	}

	/**
	 * @return array
	 */
	public function buildCategoryArray()
	{
		$categories = [];
		foreach (Helpers::getCategoryList() AS $category)
		{
			$categories[ $category['ik'] ] = ['title' => $category['title'], 'children' => []];
		}

		return $categories;
	}
}
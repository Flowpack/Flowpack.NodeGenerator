<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Media\Domain\Model\Image;
use TYPO3\Media\Domain\Model\ImageVariant;
use TYPO3\Media\Domain\Repository\ImageRepository;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * Node Generator
 */
abstract class AstractNodeGeneratorImplementation implements NodeGeneratorImplementationInterface {

	/**
	 * @Flow\Inject
	 * @var ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @Flow\Inject
	 * @var ImageRepository
	 */
	protected $imageRepository;

	/**
	 * @return ImageVariant
	 */
	protected function getRandommImageVariant() {
		$image = new Image($this->resourceManager->importResource(sprintf('resource://Flowpack.NodeGenerator/Private/Images/Sample%d.jpg', rand(1,3))));
		$this->imageRepository->add($image);

		return $image->createImageVariant(array(
			array(
				'command' => 'thumbnail',
				'options' => array(
					'size' => array(
						'width' => 1500,
						'height' => 1024
					)
				),
			),
		));
	}

	/**
	 * @param NodeInterface $parentNode
	 * @param NodeType $nodeType
	 * @return NodeInterface The freshly created node
	 */
	abstract public function create(NodeInterface $parentNode, NodeType $nodeType);
}
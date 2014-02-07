<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use TYPO3\Faker\Date;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Media\Domain\Model\Image;
use TYPO3\Media\Domain\Repository\ImageRepository;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * Post Node Generator
 */
class PostGeneratorImplementation extends PageGeneratorImplementation {

	/**
	 * @param NodeInterface $parentNode
	 * @param NodeType $nodeType
	 * @return NodeInterface|void
	 */
	public function create(NodeInterface $parentNode, NodeType $nodeType) {
		$postNode = parent::create($parentNode, $nodeType);
		$date = Date::random('-1 week');
		$postNode->setProperty('datePublished', $date);
		$postNode->setProperty('image', $this->getRandommImageVariant());

		return $postNode;
	}
}
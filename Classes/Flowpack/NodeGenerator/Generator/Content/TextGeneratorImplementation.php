<?php
namespace Flowpack\NodeGenerator\Generator\Content;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\AstractNodeGeneratorImplementation;
use KDambekalns\Faker\Lorem;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * Text Node Generator
 */
class TextGeneratorImplementation extends AstractNodeGeneratorImplementation {

	/**
	 * @param NodeInterface $parentNode
	 * @param NodeType $nodeType
	 * @return NodeInterface
	 */
	public function create(NodeInterface $parentNode, NodeType $nodeType) {
		$contentNode = $parentNode->createNode(uniqid('node'), $nodeType);
		$contentNode->setProperty('text', sprintf('<p>%s</p>', Lorem::paragraph(rand(1, 10))));

		return $contentNode;
	}
}
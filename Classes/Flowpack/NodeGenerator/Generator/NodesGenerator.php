<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use TYPO3\Faker\Company;
use TYPO3\Faker\Lorem;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Utility;

/**
 * Node Generator
 */
class NodesGenerator {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @var PresetDefinition
	 */
	protected $preset;

	/**
	 * @param PresetDefinition $preset
	 */
	function __construct(PresetDefinition $preset) {
		$this->preset = $preset;
	}

	public function generate() {
		$siteNode = $this->preset->getSiteNode();
		$this->createBatchNode($siteNode);
	}

	/**
	 * @param NodeInterface $baseNode
	 * @param int $level
	 */
	protected function createBatchNode(NodeInterface $baseNode, $level = 0) {
		$documentNodeType = $this->nodeTypeManager->getNodeType($this->preset->getDocumentNodeType());
		$contentNodeType = $this->nodeTypeManager->getNodeType($this->preset->getContentNodeType());
		for ($i = 0; $i < $this->preset->getNodeByLevel(); $i++) {
			$title = Company::name();
			$name = Utility::renderValidNodeName($title);
			if (!($baseNode->getNode($name))) {
				$childrenNode = $baseNode->createNode($name, $documentNodeType);
				$childrenNode->setProperty('title', $title);
				$mainContentCollection = $childrenNode->getNode('main');
				for ($j = 0; $j < $this->preset->getContentNodeByDocument(); $j++) {
					$contentNode = $mainContentCollection->createNode(uniqid('node'), $contentNodeType);
					$contentNode->setProperty('text', sprintf('<p>%s</p>', Lorem::paragraph(rand(1, 4))));
				}
				if ($level < $this->preset->getDepth()) {
					$level++;
					$this->createBatchNode($childrenNode, $level);
				}
			}
		}
	}

}

?>
<?php
namespace Flowpack\NodeGenerator\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\TYPO3CR\Domain\Model\Node;

class SiteCommandController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Repository\SiteRepository
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Creates a big collection of node for performance benchmarking
	 * @param string $siteNode
	 */
	public function generateNodesCommand($siteNode) {
		/** @var ContentContext $contentContext */
		$contentContext = $this->createContext('live');

		/** @var Site $site */
		$site = $this->siteRepository->findOneByNodeName($siteNode);
		if ($site === NULL) {
			$this->outputLine('Error: No site for exporting found');
			$this->quit(1);
		}
		ObjectAccess::setProperty($contentContext, 'currentSite', $site, TRUE);

		$workspace = 'live';
		if ($this->workspaceRepository->findByName($workspace)->count() === 0) {
			$this->outputLine('Workspace "%s" does not exist', array($workspace));
			$this->quit(1);
		}

		/** @var Node $siteNode */
		$siteNode = $contentContext->getCurrentSiteNode();
		if ($siteNode === NULL) {
			$this->outputLine('Error: No site root node');
			$this->quit(1);
		}

		foreach ($siteNode->getChildNodes('Ttree.Blog:Post') as $node) {
			/** @var Node $node */
			$node->remove();
		}
		$this->createBatchNode($siteNode, 'Ttree.Blog:Post', 20);
	}

	/**
	 * @param Node $baseNode
	 * @param string $nodeType
	 * @param int $limit
	 * @param int $level
	 */
	protected function createBatchNode(Node $baseNode, $nodeType, $limit, $level = 0) {
		$nodeType = $this->nodeTypeManager->getNodeType($nodeType);
		for ($i = 0; $i < $limit; $i++) {
			$name = 'foo' . $i;
			if (!($baseNode->getNode($name))) {
				$childrenNode = $baseNode->createNode($name, $nodeType);
				if ($level < 8) {
					$level++;
					$this->createBatchNode($childrenNode, 'Ttree.Blog:Post', $limit * 2, $level);
				}
			}
		}
	}

	/**
	 * @return \TYPO3\TYPO3CR\Domain\Service\ContextInterface
	 */
	protected function createContext() {
		return $this->contextFactory->create(array(
			'workspaceName' => 'live',
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		));
	}

}

?>
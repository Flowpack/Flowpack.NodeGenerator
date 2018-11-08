<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Neos\ContentRepository\Exception\NodeTypeNotFoundException;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\ContentRepository\Exception\NodeExistsException;

/**
 * Node Generator
 */
class NodesGenerator
{
    /**
     * @Flow\Inject
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var PresetDefinition
     */
    protected $preset;

    /**
     * @Flow\InjectConfiguration(path="generator", package="Flowpack.NodeGenerator")
     * @var array
     */
    protected $generators;

    /**
     * @param PresetDefinition $preset
     */
    public function __construct(PresetDefinition $preset)
    {
        $this->preset = $preset;
    }

    /**
     * @throws Exception
     * @throws NodeTypeNotFoundException
     */
    public function generate()
    {
        $siteNode = $this->preset->getSiteNode();
        $this->createBatchDocumentNode($siteNode);
    }

    /**
     * @param NodeType $nodeType
     * @return NodeGeneratorImplementationInterface
     * @throws Exception
     */
    protected function getNodeGeneratorImplementationClassByNodeType(NodeType $nodeType)
    {
        if (!isset($this->generators[(string)$nodeType]['class'])) {
            throw new Exception(sprintf('Unknown generator for the current Node Type (%s)', (string)$nodeType, 1391771111));
        }
        return $this->objectManager->get($this->generators[(string)$nodeType]['class']);
    }

    /**
     * @param NodeInterface $baseNode
     * @param int $level
     * @throws Exception
     * @throws NodeTypeNotFoundException
     */
    protected function createBatchDocumentNode(NodeInterface $baseNode, $level = 0)
    {
        for ($i = 0; $i < $this->preset->getNodeByLevel(); $i++) {
            try {
                $nodeType = $this->nodeTypeManager->getNodeType($this->preset->getDocumentNodeType());
                $generator = $this->getNodeGeneratorImplementationClassByNodeType($nodeType);
                $childrenNode = $generator->create($baseNode, $nodeType);
                $this->createBatchContentNodes($childrenNode);
                if ($level < $this->preset->getDepth()) {
                    $level++;
                    $this->createBatchDocumentNode($childrenNode, $level);
                }
            } catch (NodeExistsException $e) {
            }
        }
    }

    /**
     * @param NodeInterface $documentNode
     * @throws Exception
     * @throws NodeTypeNotFoundException
     */
    protected function createBatchContentNodes(NodeInterface $documentNode)
    {
        $mainContentCollection = $documentNode->getNode('main');
        for ($j = 0; $j < $this->preset->getContentNodeByDocument(); $j++) {
            try {
                $nodeType = $this->nodeTypeManager->getNodeType($this->preset->getContentNodeType());
                $generator = $this->getNodeGeneratorImplementationClassByNodeType($nodeType);
                $generator->create($mainContentCollection, $nodeType);
            } catch (NodeExistsException $e) {
            }
        }
    }
}

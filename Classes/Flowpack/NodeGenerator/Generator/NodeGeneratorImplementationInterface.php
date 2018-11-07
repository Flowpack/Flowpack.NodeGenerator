<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;

/**
 * Node Generator
 */
interface NodeGeneratorImplementationInterface
{
    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface The freshly created node
     */
    public function create(NodeInterface $parentNode, NodeType $nodeType);
}

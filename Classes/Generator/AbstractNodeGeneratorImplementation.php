<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\Exception;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\Adjustment\ResizeImageAdjustment;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\ImageVariant;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;

/**
 * Node Generator
 */
abstract class AbstractNodeGeneratorImplementation implements NodeGeneratorImplementationInterface
{
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
     * @throws Exception
     */
    protected function getRandomImageVariant()
    {
        $image = new Image($this->resourceManager->importResource(sprintf('resource://Flowpack.NodeGenerator/Private/Images/Sample%d.jpg', rand(1, 3))));
        $this->imageRepository->add($image);

        $newImageVariant = new ImageVariant($image);
        $resizeImageAdjustment = new ResizeImageAdjustment();
        $resizeImageAdjustment->setWidth(1024);
        $resizeImageAdjustment->setHeight(1500);

        $newImageVariant->addAdjustment($resizeImageAdjustment);

        $image->addVariant($newImageVariant);
        return $newImageVariant;
    }

    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface The freshly created node
     */
    abstract public function create(NodeInterface $parentNode, NodeType $nodeType);
}

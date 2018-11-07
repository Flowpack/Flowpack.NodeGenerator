<?php
namespace Flowpack\NodeGenerator\Command;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\NodesGenerator;
use Flowpack\NodeGenerator\Generator\PresetDefinition;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Domain\Model\Site;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Model\Node;

/**
 * Generator Controller
 */
class GeneratorCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Repository\SiteRepository
     */
    protected $siteRepository;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
     */
    protected $workspaceRepository;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\Inject(setting="preset")
     * @var array
     */
    protected $presets;

    /**
     * Creates a big collection of node for performance benchmarking
     * @param string $siteNode
     * @param string $preset
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function nodesCommand($siteNode, $preset)
    {
        if (!(isset($this->presets[$preset]))) {
            $this->outputLine('Error: Invalid preset');
            $this->quit(1);
        }
        $preset = $this->presets[$preset];
        /** @var Site $currentSite */
        $currentSite = $this->siteRepository->findOneByNodeName($siteNode);
        if ($currentSite === null) {
            $this->outputLine('Error: No site for exporting found');
            $this->quit(1);
        }
        /** @var ContentContext $contentContext */
        $contentContext = $this->createContext($currentSite, 'live');

        $workspace = 'live';
        if ($this->workspaceRepository->findByName($workspace)->count() === 0) {
            $this->outputLine('Workspace "%s" does not exist', array($workspace));
            $this->quit(1);
        }

        /** @var Node $siteNode */
        $siteNode = $contentContext->getCurrentSiteNode();
        if ($siteNode === null) {
            $this->outputLine('Error: No site root node');
            $this->quit(1);
        }
        $preset = new PresetDefinition($siteNode, $preset);
        $generator = new NodesGenerator($preset);

        $generator->generate();
    }

    /**
     * @param Site $currentSite
     * @param string $workspace
     * @return Context
     */
    protected function createContext(Site $currentSite, $workspace = 'live')
    {
        return $this->contextFactory->create(array(
            'workspaceName' => $workspace,
            'currentSite' => $currentSite,
            'invisibleContentShown' => true,
            'inaccessibleContentShown' => true
        ));
    }
}

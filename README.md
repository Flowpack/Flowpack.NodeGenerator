# NodeGenerator - Random nodes generator for Neos CMS

## Configuration of the Node generator classes

In your Settings.yaml you can register a node generator class. Each node type used in your setup, must
have an attached generator class.

```yaml
Flowpack:
  NodeGenerator:
    generator:
      'Neos.NodeTypes:Page':
        class: 'Flowpack\NodeGenerator\Generator\Document\PageGeneratorImplementation'
      'Neos.NodeTypes:Text':
        class: 'Flowpack\NodeGenerator\Generator\Content\TextGeneratorImplementation'
      'Neos.NodeTypes:Image':
        class: 'Flowpack\NodeGenerator\Generator\Content\ImageGeneratorImplementation'
      'Neos.NodeTypes:TextWithImage':
        class: 'Flowpack\NodeGenerator\Generator\Content\TextWithImageGeneratorImplementation'
```

### Minimal Generator Class

The NodesGenerators who call your node generator class, will catch NodeExistsException so you
don't need to take care about that. The generator will skip silently nodes that currently exist
in the content repository.

```php
class PageGeneratorImplementation extends AbstractNodeGeneratorImplementation {

	/**
	 * @param NodeInterface $parentNode
	 * @param NodeType $nodeType
	 * @return NodeInterface
	 */
	public function create(NodeInterface $parentNode, NodeType $nodeType) {
		$title = Company::name();
		$name = Utility::renderValidNodeName($title);

		$childrenNode = $parentNode->createNode($name, $nodeType);
		$childrenNode->setProperty('title', $title);

		return $childrenNode;
	}
}
```

## Configuration of presets

If multiple Content and Document node types are configured, the generator will select a
random node type for each new node. Take care to declare a generator class for each node
type.

The Extension is shipped with some examples of presets, a basic preset looks like:

```yaml
Flowpack:
  NodeGenerator:
    preset:
      # Basic website, with a multiple level page tree
      small-website:
        depth: 3
        nodeByLevel: 10
        contentNodeByDocument: 5
        documentNodeType: [ 'Neos.NodeTypes:Page' ]
        contentNodeType: [ 'Neos.NodeTypes:Text', 'Neos.NodeTypes:Images' ]
        # Randomness of the number of nodes generated from 0 to 100
        randomness: 25
```

## Run your preset

```
./flow generator:nodes --site-node blog --preset small-blog
```

## Configure the root node

By default all the generated pages will be created on the root-level
of the site. This behavior can be changed by providing a specific path
to an existing node:

```
flow generator:nodes --site-node homepage --preset small-blog --path blog
```


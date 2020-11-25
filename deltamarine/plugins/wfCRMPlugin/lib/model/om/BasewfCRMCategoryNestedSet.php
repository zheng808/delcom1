<?php

require 'plugins/wfCRMPlugin/lib/model/om/BasewfCRMCategory.php';

/**
 * Base class that represents a row from the 'wf_crm_category' table.
 *
 * 
 *
 * @package    plugins.wfCRMPlugin.lib.model.om
 */
abstract class BasewfCRMCategoryNestedSet extends BasewfCRMCategory implements NodeObject {

	/**
	 * Store level of node
	 * @var        int
	 */
	protected $level = null;

	/**
	 * Store if node has prev sibling
	 * @var        bool
	 */
	protected $hasPrevSibling = null;

	/**
	 * Store node if has prev sibling
	 * @var        wfCRMCategory
	 */
	protected $prevSibling = null;

	/**
	 * Store if node has next sibling
	 * @var        bool
	 */
	protected $hasNextSibling = null;

	/**
	 * Store node if has next sibling
	 * @var        wfCRMCategory
	 */
	protected $nextSibling = null;

	/**
	 * Store if node has parent node
	 * @var        bool
	 */
	protected $hasParentNode = null;

	/**
	 * The parent node for this node.
	 * @var        wfCRMCategory
	 */
	protected $parentNode = null;

	/**
	 * Store children of the node
	 * @var        array
	 */
	protected $_children = null;

	/**
	 * Returns a pre-order iterator for this node and its children.
	 *
	 * @return     NodeIterator
	 */
	public function getIterator()
	{
		return new NestedSetRecursiveIterator($this);
	}

	/**
	 * Saves modified object data to the datastore.
	 * If object is saved without left/right values, set them as undefined (0)
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 *                 May be unreliable with parent/children/brother changes
	 * @throws     PropelException
	 */
	public function save(PropelPDO $con = null)
	{
		$left = $this->getLeftValue();
		$right = $this->getRightValue();
		if (empty($left) || empty($right)) {
			$root = wfCRMCategoryPeer::retrieveRoot($this->getScopeIdValue(), $con);
			wfCRMCategoryPeer::insertAsLastChildOf($this, $root, $con);
		}

		return parent::save($con);
	}

	/**
	 * Removes this object and all descendants from datastore.
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     void
	 * @throws     PropelException
	 */
	public function delete(PropelPDO $con = null)
	{
		// delete node first
		parent::delete($con);

		// delete descendants and then shift tree
		wfCRMCategoryPeer::deleteDescendants($this, $con);
	}

	/**
	 * Sets node properties to make it a root node.
	 *
	 * @return     wfCRMCategory The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function makeRoot()
	{
		wfCRMCategoryPeer::createRoot($this);
		return $this;
	}

	/**
	 * Gets the level if set, otherwise calculates this and returns it
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int
	 */
	public function getLevel(PropelPDO $con = null)
	{
		if (null === $this->level) {
			$this->level = wfCRMCategoryPeer::getLevel($this, $con);
		}
		return $this->level;
	}

	/**
	 * Get the path to the node in the tree
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     array
	 */
	public function getPath(PropelPDO $con = null)
	{
		return wfCRMCategoryPeer::getPath($this, $con);
	}

	/**
	 * Gets the number of children for the node (direct descendants)
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int
	 */
	public function getNumberOfChildren(PropelPDO $con = null)
	{
		return wfCRMCategoryPeer::getNumberOfChildren($this, $con);
	}

	/**
	 * Gets the total number of descendants for the node
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int
	 */
	public function getNumberOfDescendants(PropelPDO $con = null)
	{
		return wfCRMCategoryPeer::getNumberOfDescendants($this, $con);
	}

	/**
	 * Gets the children for the node
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     array
	 */
	public function getChildren(PropelPDO $con = null)
	{
		$this->getLevel();

		if (is_array($this->_children)) {
			return $this->_children;
		}

		return wfCRMCategoryPeer::retrieveChildren($this, $con);
	}

	/**
	 * Gets the descendants for the node
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     array
	 */
	public function getDescendants(PropelPDO $con = null)
	{
		$this->getLevel();

		return wfCRMCategoryPeer::retrieveDescendants($this, $con);
	}

	/**
	 * Sets the level of the node in the tree
	 *
	 * @param      int $v new value
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setLevel($level)
	{
		$this->level = $level;
		return $this;
	}

	/**
	 * Sets the children array of the node in the tree
	 *
	 * @param      array of wfCRMCategory $children	array of Propel node object
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setChildren(array $children)
	{
		$this->_children = $children;
		return $this;
	}

	/**
	 * Sets the parentNode of the node in the tree
	 *
	 * @param      wfCRMCategory $parent Propel node object
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setParentNode(NodeObject $parent = null)
	{
		$this->parentNode = (true === ($this->hasParentNode = wfCRMCategoryPeer::isValid($parent))) ? $parent : null;
		return $this;
	}

	/**
	 * Sets the previous sibling of the node in the tree
	 *
	 * @param      wfCRMCategory $node Propel node object
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setPrevSibling(NodeObject $node = null)
	{
		$this->prevSibling = $node;
		$this->hasPrevSibling = wfCRMCategoryPeer::isValid($node);
		return $this;
	}

	/**
	 * Sets the next sibling of the node in the tree
	 *
	 * @param      wfCRMCategory $node Propel node object
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setNextSibling(NodeObject $node = null)
	{
		$this->nextSibling = $node;
		$this->hasNextSibling = wfCRMCategoryPeer::isValid($node);
		return $this;
	}

	/**
	 * Returns true if node is the root node of the tree.
	 *
	 * @return     bool
	 */
	public function isRoot()
	{
		return wfCRMCategoryPeer::isRoot($this);
	}

	/**
	 * Return true if the node is a leaf node
	 *
	 * @return     bool
	 */
	public function isLeaf()
	{
		return wfCRMCategoryPeer::isLeaf($this);
	}

	/**
	 * Tests if object is equal to $node
	 *
	 * @param      object $node		Propel object for node to compare to
	 * @return     bool
	 */
	public function isEqualTo(NodeObject $node)
	{
		return wfCRMCategoryPeer::isEqualTo($this, $node);
	}

	/**
	 * Tests if object has an ancestor
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     bool
	 */
	public function hasParent(PropelPDO $con = null)
	{
		if (null === $this->hasParentNode) {
			wfCRMCategoryPeer::hasParent($this, $con);
		}
		return $this->hasParentNode;
	}

	/**
	 * Determines if the node has children / descendants
	 *
	 * @return     bool
	 */
	public function hasChildren()
	{
		return  wfCRMCategoryPeer::hasChildren($this);
	}

	/**
	 * Determines if the node has previous sibling
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     bool
	 */
	public function hasPrevSibling(PropelPDO $con = null)
	{
		if (null === $this->hasPrevSibling) {
			wfCRMCategoryPeer::hasPrevSibling($this, $con);
		}
		return $this->hasPrevSibling;
	}

	/**
	 * Determines if the node has next sibling
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     bool
	 */
	public function hasNextSibling(PropelPDO $con = null)
	{
		if (null === $this->hasNextSibling) {
			wfCRMCategoryPeer::hasNextSibling($this, $con);
		}
		return $this->hasNextSibling;
	}

	/**
	 * Gets ancestor for the given node if it exists
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public function retrieveParent(PropelPDO $con = null)
	{
		if (null === $this->hasParentNode) {
			$this->parentNode = wfCRMCategoryPeer::retrieveParent($this, $con);
			$this->hasParentNode = wfCRMCategoryPeer::isValid($this->parentNode);
		}
		return $this->parentNode;
	}

	/**
	 * Gets first child if it exists
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public function retrieveFirstChild(PropelPDO $con = null)
	{
		if ($this->hasChildren($con)) {
			if (is_array($this->_children)) {
				return $this->_children[0];
			}

			return wfCRMCategoryPeer::retrieveFirstChild($this, $con);
		}
		return false;
	}

	/**
	 * Gets last child if it exists
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public function retrieveLastChild(PropelPDO $con = null)
	{
		if ($this->hasChildren($con)) {
			if (is_array($this->_children)) {
				$last = count($this->_children) - 1;
				return $this->_children[$last];
			}

			return wfCRMCategoryPeer::retrieveLastChild($this, $con);
		}
		return false;
	}

	/**
	 * Gets prev sibling for the given node if it exists
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public function retrievePrevSibling(PropelPDO $con = null)
	{
		if ($this->hasPrevSibling($con)) {
			return $this->prevSibling;
		}
		return $this->hasPrevSibling;
	}

	/**
	 * Gets next sibling for the given node if it exists
	 *
	 * @param      PropelPDO $con Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public function retrieveNextSibling(PropelPDO $con = null)
	{
		if ($this->hasNextSibling($con)) {
			return $this->nextSibling;
		}
		return $this->hasNextSibling;
	}

	/**
	 * Inserts as first child of given destination node $parent
	 *
	 * @param      wfCRMCategory $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsFirstChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("wfCRMCategory must be new.");
		}
		wfCRMCategoryPeer::insertAsFirstChildOf($this, $parent, $con);
		return $this;
	}

	/**
	 * Inserts as last child of given destination node $parent
	 *
	 * @param      wfCRMCategory $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsLastChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("wfCRMCategory must be new.");
		}
		wfCRMCategoryPeer::insertAsLastChildOf($this, $parent, $con);
		return $this;
	}

	/**
	 * Inserts $node as previous sibling to given destination node $dest
	 *
	 * @param      wfCRMCategory $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsPrevSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("wfCRMCategory must be new.");
		}
		wfCRMCategoryPeer::insertAsPrevSiblingOf($this, $dest, $con);
		return $this;
	}

	/**
	 * Inserts $node as next sibling to given destination node $dest
	 *
	 * @param      wfCRMCategory $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsNextSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("wfCRMCategory must be new.");
		}
		wfCRMCategoryPeer::insertAsNextSiblingOf($this, $dest, $con);
		return $this;
	}

	/**
	 * Moves node to be first child of $parent
	 *
	 * @param      wfCRMCategory $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function moveToFirstChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("wfCRMCategory must exist in tree.");
		}
		wfCRMCategoryPeer::moveToFirstChildOf($parent, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be last child of $parent
	 *
	 * @param      wfCRMCategory $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function moveToLastChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("wfCRMCategory must exist in tree.");
		}
		wfCRMCategoryPeer::moveToLastChildOf($parent, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be prev sibling to $dest
	 *
	 * @param      wfCRMCategory $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function moveToPrevSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("wfCRMCategory must exist in tree.");
		}
		wfCRMCategoryPeer::moveToPrevSiblingOf($dest, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be next sibling to $dest
	 *
	 * @param      wfCRMCategory $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function moveToNextSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("wfCRMCategory must exist in tree.");
		}
		wfCRMCategoryPeer::moveToNextSiblingOf($dest, $this, $con);
		return $this;
	}

	/**
	 * Inserts node as parent of given node.
	 *
	 * @param      wfCRMCategory $node Propel object for destination node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function insertAsParentOf(NodeObject $node, PropelPDO $con = null)
	{
		wfCRMCategoryPeer::insertAsParentOf($this, $node, $con);
		return $this;
	}

	/**
	 * Wraps the getter for the left value
	 *
	 * @return     int
	 */
	public function getLeftValue()
	{
		return $this->getTreeLeft();
	}

	/**
	 * Wraps the getter for the right value
	 *
	 * @return     int
	 */
	public function getRightValue()
	{
		return $this->getTreeRight();
	}

	/**
	 * Wraps the getter for the scope value
	 *
	 * @return     int or null if scope is disabled
	 */
	public function getScopeIdValue()
	{
		return $this->getTreeId();
	}

	/**
	 * Set the value left column
	 *
	 * @param      int $v new value
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setLeftValue($v)
	{
		$this->setTreeLeft($v);
		return $this;
	}

	/**
	 * Set the value of right column
	 *
	 * @param      int $v new value
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setRightValue($v)
	{
		$this->setTreeRight($v);
		return $this;
	}

	/**
	 * Set the value of scope column
	 *
	 * @param      int $v new value
	 * @return     wfCRMCategory The current object (for fluent API support)
	 */
	public function setScopeIdValue($v)
	{
		$this->setTreeId($v);
		return $this;
	}

} // BasewfCRMCategoryNestedSet

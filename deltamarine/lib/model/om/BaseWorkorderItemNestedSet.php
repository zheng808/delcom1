<?php

require 'lib/model/om/BaseWorkorderItem.php';

/**
 * Base class that represents a row from the 'workorder_item' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorderItemNestedSet extends BaseWorkorderItem implements NodeObject {

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
	 * @var        WorkorderItem
	 */
	protected $prevSibling = null;

	/**
	 * Store if node has next sibling
	 * @var        bool
	 */
	protected $hasNextSibling = null;

	/**
	 * Store node if has next sibling
	 * @var        WorkorderItem
	 */
	protected $nextSibling = null;

	/**
	 * Store if node has parent node
	 * @var        bool
	 */
	protected $hasParentNode = null;

	/**
	 * The parent node for this node.
	 * @var        WorkorderItem
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
			$root = WorkorderItemPeer::retrieveRoot($this->getScopeIdValue(), $con);
			WorkorderItemPeer::insertAsLastChildOf($this, $root, $con);
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
		WorkorderItemPeer::deleteDescendants($this, $con);
	}

	/**
	 * Sets node properties to make it a root node.
	 *
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function makeRoot()
	{
		WorkorderItemPeer::createRoot($this);
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
			$this->level = WorkorderItemPeer::getLevel($this, $con);
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
		return WorkorderItemPeer::getPath($this, $con);
	}

	/**
	 * Gets the number of children for the node (direct descendants)
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int
	 */
	public function getNumberOfChildren(PropelPDO $con = null)
	{
		return WorkorderItemPeer::getNumberOfChildren($this, $con);
	}

	/**
	 * Gets the total number of descendants for the node
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     int
	 */
	public function getNumberOfDescendants(PropelPDO $con = null)
	{
		return WorkorderItemPeer::getNumberOfDescendants($this, $con);
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

		return WorkorderItemPeer::retrieveChildren($this, $con);
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

		return WorkorderItemPeer::retrieveDescendants($this, $con);
	}

	/**
	 * Sets the level of the node in the tree
	 *
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLevel($level)
	{
		$this->level = $level;
		return $this;
	}

	/**
	 * Sets the children array of the node in the tree
	 *
	 * @param      array of WorkorderItem $children	array of Propel node object
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setChildren(array $children)
	{
		$this->_children = $children;
		return $this;
	}

	/**
	 * Sets the parentNode of the node in the tree
	 *
	 * @param      WorkorderItem $parent Propel node object
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setParentNode(NodeObject $parent = null)
	{
		$this->parentNode = (true === ($this->hasParentNode = WorkorderItemPeer::isValid($parent))) ? $parent : null;
		return $this;
	}

	/**
	 * Sets the previous sibling of the node in the tree
	 *
	 * @param      WorkorderItem $node Propel node object
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setPrevSibling(NodeObject $node = null)
	{
		$this->prevSibling = $node;
		$this->hasPrevSibling = WorkorderItemPeer::isValid($node);
		return $this;
	}

	/**
	 * Sets the next sibling of the node in the tree
	 *
	 * @param      WorkorderItem $node Propel node object
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setNextSibling(NodeObject $node = null)
	{
		$this->nextSibling = $node;
		$this->hasNextSibling = WorkorderItemPeer::isValid($node);
		return $this;
	}

	/**
	 * Returns true if node is the root node of the tree.
	 *
	 * @return     bool
	 */
	public function isRoot()
	{
		return WorkorderItemPeer::isRoot($this);
	}

	/**
	 * Return true if the node is a leaf node
	 *
	 * @return     bool
	 */
	public function isLeaf()
	{
		return WorkorderItemPeer::isLeaf($this);
	}

	/**
	 * Tests if object is equal to $node
	 *
	 * @param      object $node		Propel object for node to compare to
	 * @return     bool
	 */
	public function isEqualTo(NodeObject $node)
	{
		return WorkorderItemPeer::isEqualTo($this, $node);
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
			WorkorderItemPeer::hasParent($this, $con);
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
		return  WorkorderItemPeer::hasChildren($this);
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
			WorkorderItemPeer::hasPrevSibling($this, $con);
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
			WorkorderItemPeer::hasNextSibling($this, $con);
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
			$this->parentNode = WorkorderItemPeer::retrieveParent($this, $con);
			$this->hasParentNode = WorkorderItemPeer::isValid($this->parentNode);
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

			return WorkorderItemPeer::retrieveFirstChild($this, $con);
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

			return WorkorderItemPeer::retrieveLastChild($this, $con);
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
	 * @param      WorkorderItem $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsFirstChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("WorkorderItem must be new.");
		}
		WorkorderItemPeer::insertAsFirstChildOf($this, $parent, $con);
		return $this;
	}

	/**
	 * Inserts as last child of given destination node $parent
	 *
	 * @param      WorkorderItem $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsLastChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("WorkorderItem must be new.");
		}
		WorkorderItemPeer::insertAsLastChildOf($this, $parent, $con);
		return $this;
	}

	/**
	 * Inserts $node as previous sibling to given destination node $dest
	 *
	 * @param      WorkorderItem $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsPrevSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("WorkorderItem must be new.");
		}
		WorkorderItemPeer::insertAsPrevSiblingOf($this, $dest, $con);
		return $this;
	}

	/**
	 * Inserts $node as next sibling to given destination node $dest
	 *
	 * @param      WorkorderItem $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException - if this object already exists
	 */
	public function insertAsNextSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if (!$this->isNew())
		{
			throw new PropelException("WorkorderItem must be new.");
		}
		WorkorderItemPeer::insertAsNextSiblingOf($this, $dest, $con);
		return $this;
	}

	/**
	 * Moves node to be first child of $parent
	 *
	 * @param      WorkorderItem $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function moveToFirstChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("WorkorderItem must exist in tree.");
		}
		WorkorderItemPeer::moveToFirstChildOf($parent, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be last child of $parent
	 *
	 * @param      WorkorderItem $parent	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function moveToLastChildOf(NodeObject $parent, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("WorkorderItem must exist in tree.");
		}
		WorkorderItemPeer::moveToLastChildOf($parent, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be prev sibling to $dest
	 *
	 * @param      WorkorderItem $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function moveToPrevSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("WorkorderItem must exist in tree.");
		}
		WorkorderItemPeer::moveToPrevSiblingOf($dest, $this, $con);
		return $this;
	}

	/**
	 * Moves node to be next sibling to $dest
	 *
	 * @param      WorkorderItem $dest	Propel object for destination node
	 * @param      PropelPDO $con Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function moveToNextSiblingOf(NodeObject $dest, PropelPDO $con = null)
	{
		if ($this->isNew())
		{
			throw new PropelException("WorkorderItem must exist in tree.");
		}
		WorkorderItemPeer::moveToNextSiblingOf($dest, $this, $con);
		return $this;
	}

	/**
	 * Inserts node as parent of given node.
	 *
	 * @param      WorkorderItem $node Propel object for destination node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function insertAsParentOf(NodeObject $node, PropelPDO $con = null)
	{
		WorkorderItemPeer::insertAsParentOf($this, $node, $con);
		return $this;
	}

	/**
	 * Wraps the getter for the left value
	 *
	 * @return     int
	 */
	public function getLeftValue()
	{
		return $this->getLeft();
	}

	/**
	 * Wraps the getter for the right value
	 *
	 * @return     int
	 */
	public function getRightValue()
	{
		return $this->getRight();
	}

	/**
	 * Wraps the getter for the scope value
	 *
	 * @return     int or null if scope is disabled
	 */
	public function getScopeIdValue()
	{
		return $this->getWorkorderId();
	}

	/**
	 * Set the value left column
	 *
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLeftValue($v)
	{
		$this->setLeft($v);
		return $this;
	}

	/**
	 * Set the value of right column
	 *
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setRightValue($v)
	{
		$this->setRight($v);
		return $this;
	}

	/**
	 * Set the value of scope column
	 *
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setScopeIdValue($v)
	{
		$this->setWorkorderId($v);
		return $this;
	}

} // BaseWorkorderItemNestedSet
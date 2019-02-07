<?php

/**
 * Base class that represents a row from the 'wf_crm' table.
 *
 * 
 *
 * @package    plugins.wfCRMPlugin.lib.model.om
 */
abstract class BasewfCRM extends BaseObject  implements Persistent {


  const PEER = 'wfCRMPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        wfCRMPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the tree_left field.
	 * @var        int
	 */
	protected $tree_left;

	/**
	 * The value for the tree_right field.
	 * @var        int
	 */
	protected $tree_right;

	/**
	 * The value for the parent_node_id field.
	 * @var        int
	 */
	protected $parent_node_id;

	/**
	 * The value for the tree_id field.
	 * @var        int
	 */
	protected $tree_id;

	/**
	 * The value for the department_name field.
	 * @var        string
	 */
	protected $department_name;

	/**
	 * The value for the first_name field.
	 * @var        string
	 */
	protected $first_name;

	/**
	 * The value for the middle_name field.
	 * @var        string
	 */
	protected $middle_name;

	/**
	 * The value for the last_name field.
	 * @var        string
	 */
	protected $last_name;

	/**
	 * The value for the salutation field.
	 * @var        string
	 */
	protected $salutation;

	/**
	 * The value for the titles field.
	 * @var        string
	 */
	protected $titles;

	/**
	 * The value for the job_title field.
	 * @var        string
	 */
	protected $job_title;

	/**
	 * The value for the alpha_name field.
	 * @var        string
	 */
	protected $alpha_name;

	/**
	 * The value for the email field.
	 * @var        string
	 */
	protected $email;

	/**
	 * The value for the work_phone field.
	 * @var        string
	 */
	protected $work_phone;

	/**
	 * The value for the mobile_phone field.
	 * @var        string
	 */
	protected $mobile_phone;

	/**
	 * The value for the home_phone field.
	 * @var        string
	 */
	protected $home_phone;

	/**
	 * The value for the fax field.
	 * @var        string
	 */
	protected $fax;

	/**
	 * The value for the homepage field.
	 * @var        string
	 */
	protected $homepage;

	/**
	 * The value for the private_notes field.
	 * @var        string
	 */
	protected $private_notes;

	/**
	 * The value for the public_notes field.
	 * @var        string
	 */
	protected $public_notes;

	/**
	 * The value for the is_company field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_company;

	/**
	 * The value for the is_in_addressbook field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $is_in_addressbook;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the updated_at field.
	 * @var        string
	 */
	protected $updated_at;

	/**
	 * @var        wfCRM
	 */
	protected $awfCRMRelatedByParentNodeId;

	/**
	 * @var        array Supplier[] Collection to store aggregation of Supplier objects.
	 */
	protected $collSuppliers;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSuppliers.
	 */
	private $lastSupplierCriteria = null;

	/**
	 * @var        array Manufacturer[] Collection to store aggregation of Manufacturer objects.
	 */
	protected $collManufacturers;

	/**
	 * @var        Criteria The criteria used to select the current contents of collManufacturers.
	 */
	private $lastManufacturerCriteria = null;

	/**
	 * @var        array Employee[] Collection to store aggregation of Employee objects.
	 */
	protected $collEmployees;

	/**
	 * @var        Criteria The criteria used to select the current contents of collEmployees.
	 */
	private $lastEmployeeCriteria = null;

	/**
	 * @var        array Customer[] Collection to store aggregation of Customer objects.
	 */
	protected $collCustomers;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomers.
	 */
	private $lastCustomerCriteria = null;

	/**
	 * @var        array wfCRM[] Collection to store aggregation of wfCRM objects.
	 */
	protected $collwfCRMsRelatedByParentNodeId;

	/**
	 * @var        Criteria The criteria used to select the current contents of collwfCRMsRelatedByParentNodeId.
	 */
	private $lastwfCRMRelatedByParentNodeIdCriteria = null;

	/**
	 * @var        array wfCRMCategoryRef[] Collection to store aggregation of wfCRMCategoryRef objects.
	 */
	protected $collwfCRMCategoryRefs;

	/**
	 * @var        Criteria The criteria used to select the current contents of collwfCRMCategoryRefs.
	 */
	private $lastwfCRMCategoryRefCriteria = null;

	/**
	 * @var        array wfCRMAddress[] Collection to store aggregation of wfCRMAddress objects.
	 */
	protected $collwfCRMAddresss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collwfCRMAddresss.
	 */
	private $lastwfCRMAddressCriteria = null;

	/**
	 * @var        array wfCRMCorrespondence[] Collection to store aggregation of wfCRMCorrespondence objects.
	 */
	protected $collwfCRMCorrespondences;

	/**
	 * @var        Criteria The criteria used to select the current contents of collwfCRMCorrespondences.
	 */
	private $lastwfCRMCorrespondenceCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Initializes internal state of BasewfCRM object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->is_company = false;
		$this->is_in_addressbook = true;
	}

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [tree_left] column value.
	 * 
	 * @return     int
	 */
	public function getTreeLeft()
	{
		return $this->tree_left;
	}

	/**
	 * Get the [tree_right] column value.
	 * 
	 * @return     int
	 */
	public function getTreeRight()
	{
		return $this->tree_right;
	}

	/**
	 * Get the [parent_node_id] column value.
	 * 
	 * @return     int
	 */
	public function getParentNodeId()
	{
		return $this->parent_node_id;
	}

	/**
	 * Get the [tree_id] column value.
	 * 
	 * @return     int
	 */
	public function getTreeId()
	{
		return $this->tree_id;
	}

	/**
	 * Get the [department_name] column value.
	 * 
	 * @return     string
	 */
	public function getDepartmentName()
	{
		return $this->department_name;
	}

	/**
	 * Get the [first_name] column value.
	 * 
	 * @return     string
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * Get the [middle_name] column value.
	 * 
	 * @return     string
	 */
	public function getMiddleName()
	{
		return $this->middle_name;
	}

	/**
	 * Get the [last_name] column value.
	 * 
	 * @return     string
	 */
	public function getLastName()
	{
		return $this->last_name;
	}

	/**
	 * Get the [salutation] column value.
	 * 
	 * @return     string
	 */
	public function getSalutation()
	{
		return $this->salutation;
	}

	/**
	 * Get the [titles] column value.
	 * 
	 * @return     string
	 */
	public function getTitles()
	{
		return $this->titles;
	}

	/**
	 * Get the [job_title] column value.
	 * 
	 * @return     string
	 */
	public function getJobTitle()
	{
		return $this->job_title;
	}

	/**
	 * Get the [alpha_name] column value.
	 * 
	 * @return     string
	 */
	public function getAlphaName()
	{
		return $this->alpha_name;
	}

	/**
	 * Get the [email] column value.
	 * 
	 * @return     string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the [work_phone] column value.
	 * 
	 * @return     string
	 */
	public function getWorkPhone()
	{
		return $this->work_phone;
	}

	/**
	 * Get the [mobile_phone] column value.
	 * 
	 * @return     string
	 */
	public function getMobilePhone()
	{
		return $this->mobile_phone;
	}

	/**
	 * Get the [home_phone] column value.
	 * 
	 * @return     string
	 */
	public function getHomePhone()
	{
		return $this->home_phone;
	}

	/**
	 * Get the [fax] column value.
	 * 
	 * @return     string
	 */
	public function getFax()
	{
		return $this->fax;
	}

	/**
	 * Get the [homepage] column value.
	 * 
	 * @return     string
	 */
	public function getHomepage()
	{
		return $this->homepage;
	}

	/**
	 * Get the [private_notes] column value.
	 * 
	 * @return     string
	 */
	public function getPrivateNotes()
	{
		return $this->private_notes;
	}

	/**
	 * Get the [public_notes] column value.
	 * 
	 * @return     string
	 */
	public function getPublicNotes()
	{
		return $this->public_notes;
	}

	/**
	 * Get the [is_company] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsCompany()
	{
		return $this->is_company;
	}

	/**
	 * Get the [is_in_addressbook] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsInAddressbook()
	{
		return $this->is_in_addressbook;
	}

	/**
	 * Get the [optionally formatted] temporal [created_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->created_at === null) {
			return null;
		}


		if ($this->created_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->created_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [optionally formatted] temporal [updated_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->updated_at === null) {
			return null;
		}


		if ($this->updated_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->updated_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = wfCRMPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [tree_left] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setTreeLeft($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->tree_left !== $v) {
			$this->tree_left = $v;
			$this->modifiedColumns[] = wfCRMPeer::TREE_LEFT;
		}

		return $this;
	} // setTreeLeft()

	/**
	 * Set the value of [tree_right] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setTreeRight($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->tree_right !== $v) {
			$this->tree_right = $v;
			$this->modifiedColumns[] = wfCRMPeer::TREE_RIGHT;
		}

		return $this;
	} // setTreeRight()

	/**
	 * Set the value of [parent_node_id] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setParentNodeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->parent_node_id !== $v) {
			$this->parent_node_id = $v;
			$this->modifiedColumns[] = wfCRMPeer::PARENT_NODE_ID;
		}

		if ($this->awfCRMRelatedByParentNodeId !== null && $this->awfCRMRelatedByParentNodeId->getId() !== $v) {
			$this->awfCRMRelatedByParentNodeId = null;
		}

		return $this;
	} // setParentNodeId()

	/**
	 * Set the value of [tree_id] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setTreeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->tree_id !== $v) {
			$this->tree_id = $v;
			$this->modifiedColumns[] = wfCRMPeer::TREE_ID;
		}

		return $this;
	} // setTreeId()

	/**
	 * Set the value of [department_name] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setDepartmentName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->department_name !== $v) {
			$this->department_name = $v;
			$this->modifiedColumns[] = wfCRMPeer::DEPARTMENT_NAME;
		}

		return $this;
	} // setDepartmentName()

	/**
	 * Set the value of [first_name] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setFirstName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->first_name !== $v) {
			$this->first_name = $v;
			$this->modifiedColumns[] = wfCRMPeer::FIRST_NAME;
		}

		return $this;
	} // setFirstName()

	/**
	 * Set the value of [middle_name] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setMiddleName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->middle_name !== $v) {
			$this->middle_name = $v;
			$this->modifiedColumns[] = wfCRMPeer::MIDDLE_NAME;
		}

		return $this;
	} // setMiddleName()

	/**
	 * Set the value of [last_name] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setLastName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->last_name !== $v) {
			$this->last_name = $v;
			$this->modifiedColumns[] = wfCRMPeer::LAST_NAME;
		}

		return $this;
	} // setLastName()

	/**
	 * Set the value of [salutation] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setSalutation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->salutation !== $v) {
			$this->salutation = $v;
			$this->modifiedColumns[] = wfCRMPeer::SALUTATION;
		}

		return $this;
	} // setSalutation()

	/**
	 * Set the value of [titles] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setTitles($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->titles !== $v) {
			$this->titles = $v;
			$this->modifiedColumns[] = wfCRMPeer::TITLES;
		}

		return $this;
	} // setTitles()

	/**
	 * Set the value of [job_title] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setJobTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->job_title !== $v) {
			$this->job_title = $v;
			$this->modifiedColumns[] = wfCRMPeer::JOB_TITLE;
		}

		return $this;
	} // setJobTitle()

	/**
	 * Set the value of [alpha_name] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setAlphaName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->alpha_name !== $v) {
			$this->alpha_name = $v;
			$this->modifiedColumns[] = wfCRMPeer::ALPHA_NAME;
		}

		return $this;
	} // setAlphaName()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = wfCRMPeer::EMAIL;
		}

		return $this;
	} // setEmail()

	/**
	 * Set the value of [work_phone] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setWorkPhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->work_phone !== $v) {
			$this->work_phone = $v;
			$this->modifiedColumns[] = wfCRMPeer::WORK_PHONE;
		}

		return $this;
	} // setWorkPhone()

	/**
	 * Set the value of [mobile_phone] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setMobilePhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->mobile_phone !== $v) {
			$this->mobile_phone = $v;
			$this->modifiedColumns[] = wfCRMPeer::MOBILE_PHONE;
		}

		return $this;
	} // setMobilePhone()

	/**
	 * Set the value of [home_phone] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setHomePhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->home_phone !== $v) {
			$this->home_phone = $v;
			$this->modifiedColumns[] = wfCRMPeer::HOME_PHONE;
		}

		return $this;
	} // setHomePhone()

	/**
	 * Set the value of [fax] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setFax($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->fax !== $v) {
			$this->fax = $v;
			$this->modifiedColumns[] = wfCRMPeer::FAX;
		}

		return $this;
	} // setFax()

	/**
	 * Set the value of [homepage] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setHomepage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->homepage !== $v) {
			$this->homepage = $v;
			$this->modifiedColumns[] = wfCRMPeer::HOMEPAGE;
		}

		return $this;
	} // setHomepage()

	/**
	 * Set the value of [private_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setPrivateNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->private_notes !== $v) {
			$this->private_notes = $v;
			$this->modifiedColumns[] = wfCRMPeer::PRIVATE_NOTES;
		}

		return $this;
	} // setPrivateNotes()

	/**
	 * Set the value of [public_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setPublicNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->public_notes !== $v) {
			$this->public_notes = $v;
			$this->modifiedColumns[] = wfCRMPeer::PUBLIC_NOTES;
		}

		return $this;
	} // setPublicNotes()

	/**
	 * Set the value of [is_company] column.
	 * 
	 * @param      boolean $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setIsCompany($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_company !== $v || $v === false) {
			$this->is_company = $v;
			$this->modifiedColumns[] = wfCRMPeer::IS_COMPANY;
		}

		return $this;
	} // setIsCompany()

	/**
	 * Set the value of [is_in_addressbook] column.
	 * 
	 * @param      boolean $v new value
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setIsInAddressbook($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_in_addressbook !== $v || $v === true) {
			$this->is_in_addressbook = $v;
			$this->modifiedColumns[] = wfCRMPeer::IS_IN_ADDRESSBOOK;
		}

		return $this;
	} // setIsInAddressbook()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setCreatedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->created_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = wfCRMPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     wfCRM The current object (for fluent API support)
	 */
	public function setUpdatedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->updated_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->updated_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = wfCRMPeer::UPDATED_AT;
			}
		} // if either are not null

		return $this;
	} // setUpdatedAt()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			// First, ensure that we don't have any columns that have been modified which aren't default columns.
			if (array_diff($this->modifiedColumns, array(wfCRMPeer::IS_COMPANY,wfCRMPeer::IS_IN_ADDRESSBOOK))) {
				return false;
			}

			if ($this->is_company !== false) {
				return false;
			}

			if ($this->is_in_addressbook !== true) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->tree_left = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->tree_right = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->parent_node_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->tree_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->department_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->first_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->middle_name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->last_name = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->salutation = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->titles = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->job_title = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->alpha_name = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->email = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->work_phone = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->mobile_phone = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->home_phone = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->fax = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
			$this->homepage = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
			$this->private_notes = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
			$this->public_notes = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
			$this->is_company = ($row[$startcol + 21] !== null) ? (boolean) $row[$startcol + 21] : null;
			$this->is_in_addressbook = ($row[$startcol + 22] !== null) ? (boolean) $row[$startcol + 22] : null;
			$this->created_at = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
			$this->updated_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 25; // 25 = wfCRMPeer::NUM_COLUMNS - wfCRMPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating wfCRM object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->awfCRMRelatedByParentNodeId !== null && $this->parent_node_id !== $this->awfCRMRelatedByParentNodeId->getId()) {
			$this->awfCRMRelatedByParentNodeId = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(wfCRMPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = wfCRMPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->awfCRMRelatedByParentNodeId = null;
			$this->collSuppliers = null;
			$this->lastSupplierCriteria = null;

			$this->collManufacturers = null;
			$this->lastManufacturerCriteria = null;

			$this->collEmployees = null;
			$this->lastEmployeeCriteria = null;

			$this->collCustomers = null;
			$this->lastCustomerCriteria = null;

			$this->collwfCRMsRelatedByParentNodeId = null;
			$this->lastwfCRMRelatedByParentNodeIdCriteria = null;

			$this->collwfCRMCategoryRefs = null;
			$this->lastwfCRMCategoryRefCriteria = null;

			$this->collwfCRMAddresss = null;
			$this->lastwfCRMAddressCriteria = null;

			$this->collwfCRMCorrespondences = null;
			$this->lastwfCRMCorrespondenceCriteria = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasewfCRM:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(wfCRMPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			wfCRMPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasewfCRM:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasewfCRM:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(wfCRMPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(wfCRMPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(wfCRMPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasewfCRM:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			wfCRMPeer::addInstanceToPool($this);
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->awfCRMRelatedByParentNodeId !== null) {
				if ($this->awfCRMRelatedByParentNodeId->isModified() || $this->awfCRMRelatedByParentNodeId->isNew()) {
					$affectedRows += $this->awfCRMRelatedByParentNodeId->save($con);
				}
				$this->setwfCRMRelatedByParentNodeId($this->awfCRMRelatedByParentNodeId);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = wfCRMPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = wfCRMPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += wfCRMPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collSuppliers !== null) {
				foreach ($this->collSuppliers as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collManufacturers !== null) {
				foreach ($this->collManufacturers as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collEmployees !== null) {
				foreach ($this->collEmployees as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCustomers !== null) {
				foreach ($this->collCustomers as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collwfCRMsRelatedByParentNodeId !== null) {
				foreach ($this->collwfCRMsRelatedByParentNodeId as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collwfCRMCategoryRefs !== null) {
				foreach ($this->collwfCRMCategoryRefs as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collwfCRMAddresss !== null) {
				foreach ($this->collwfCRMAddresss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collwfCRMCorrespondences !== null) {
				foreach ($this->collwfCRMCorrespondences as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->awfCRMRelatedByParentNodeId !== null) {
				if (!$this->awfCRMRelatedByParentNodeId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->awfCRMRelatedByParentNodeId->getValidationFailures());
				}
			}


			if (($retval = wfCRMPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collSuppliers !== null) {
					foreach ($this->collSuppliers as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collManufacturers !== null) {
					foreach ($this->collManufacturers as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collEmployees !== null) {
					foreach ($this->collEmployees as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCustomers !== null) {
					foreach ($this->collCustomers as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collwfCRMsRelatedByParentNodeId !== null) {
					foreach ($this->collwfCRMsRelatedByParentNodeId as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collwfCRMCategoryRefs !== null) {
					foreach ($this->collwfCRMCategoryRefs as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collwfCRMAddresss !== null) {
					foreach ($this->collwfCRMAddresss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collwfCRMCorrespondences !== null) {
					foreach ($this->collwfCRMCorrespondences as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = wfCRMPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getTreeLeft();
				break;
			case 2:
				return $this->getTreeRight();
				break;
			case 3:
				return $this->getParentNodeId();
				break;
			case 4:
				return $this->getTreeId();
				break;
			case 5:
				return $this->getDepartmentName();
				break;
			case 6:
				return $this->getFirstName();
				break;
			case 7:
				return $this->getMiddleName();
				break;
			case 8:
				return $this->getLastName();
				break;
			case 9:
				return $this->getSalutation();
				break;
			case 10:
				return $this->getTitles();
				break;
			case 11:
				return $this->getJobTitle();
				break;
			case 12:
				return $this->getAlphaName();
				break;
			case 13:
				return $this->getEmail();
				break;
			case 14:
				return $this->getWorkPhone();
				break;
			case 15:
				return $this->getMobilePhone();
				break;
			case 16:
				return $this->getHomePhone();
				break;
			case 17:
				return $this->getFax();
				break;
			case 18:
				return $this->getHomepage();
				break;
			case 19:
				return $this->getPrivateNotes();
				break;
			case 20:
				return $this->getPublicNotes();
				break;
			case 21:
				return $this->getIsCompany();
				break;
			case 22:
				return $this->getIsInAddressbook();
				break;
			case 23:
				return $this->getCreatedAt();
				break;
			case 24:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = wfCRMPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getTreeLeft(),
			$keys[2] => $this->getTreeRight(),
			$keys[3] => $this->getParentNodeId(),
			$keys[4] => $this->getTreeId(),
			$keys[5] => $this->getDepartmentName(),
			$keys[6] => $this->getFirstName(),
			$keys[7] => $this->getMiddleName(),
			$keys[8] => $this->getLastName(),
			$keys[9] => $this->getSalutation(),
			$keys[10] => $this->getTitles(),
			$keys[11] => $this->getJobTitle(),
			$keys[12] => $this->getAlphaName(),
			$keys[13] => $this->getEmail(),
			$keys[14] => $this->getWorkPhone(),
			$keys[15] => $this->getMobilePhone(),
			$keys[16] => $this->getHomePhone(),
			$keys[17] => $this->getFax(),
			$keys[18] => $this->getHomepage(),
			$keys[19] => $this->getPrivateNotes(),
			$keys[20] => $this->getPublicNotes(),
			$keys[21] => $this->getIsCompany(),
			$keys[22] => $this->getIsInAddressbook(),
			$keys[23] => $this->getCreatedAt(),
			$keys[24] => $this->getUpdatedAt(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = wfCRMPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setTreeLeft($value);
				break;
			case 2:
				$this->setTreeRight($value);
				break;
			case 3:
				$this->setParentNodeId($value);
				break;
			case 4:
				$this->setTreeId($value);
				break;
			case 5:
				$this->setDepartmentName($value);
				break;
			case 6:
				$this->setFirstName($value);
				break;
			case 7:
				$this->setMiddleName($value);
				break;
			case 8:
				$this->setLastName($value);
				break;
			case 9:
				$this->setSalutation($value);
				break;
			case 10:
				$this->setTitles($value);
				break;
			case 11:
				$this->setJobTitle($value);
				break;
			case 12:
				$this->setAlphaName($value);
				break;
			case 13:
				$this->setEmail($value);
				break;
			case 14:
				$this->setWorkPhone($value);
				break;
			case 15:
				$this->setMobilePhone($value);
				break;
			case 16:
				$this->setHomePhone($value);
				break;
			case 17:
				$this->setFax($value);
				break;
			case 18:
				$this->setHomepage($value);
				break;
			case 19:
				$this->setPrivateNotes($value);
				break;
			case 20:
				$this->setPublicNotes($value);
				break;
			case 21:
				$this->setIsCompany($value);
				break;
			case 22:
				$this->setIsInAddressbook($value);
				break;
			case 23:
				$this->setCreatedAt($value);
				break;
			case 24:
				$this->setUpdatedAt($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = wfCRMPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTreeLeft($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTreeRight($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setParentNodeId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setTreeId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setDepartmentName($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setFirstName($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setMiddleName($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setLastName($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setSalutation($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setTitles($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setJobTitle($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setAlphaName($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setEmail($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setWorkPhone($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setMobilePhone($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setHomePhone($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setFax($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setHomepage($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setPrivateNotes($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setPublicNotes($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setIsCompany($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setIsInAddressbook($arr[$keys[22]]);
		if (array_key_exists($keys[23], $arr)) $this->setCreatedAt($arr[$keys[23]]);
		if (array_key_exists($keys[24], $arr)) $this->setUpdatedAt($arr[$keys[24]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);

		if ($this->isColumnModified(wfCRMPeer::ID)) $criteria->add(wfCRMPeer::ID, $this->id);
		if ($this->isColumnModified(wfCRMPeer::TREE_LEFT)) $criteria->add(wfCRMPeer::TREE_LEFT, $this->tree_left);
		if ($this->isColumnModified(wfCRMPeer::TREE_RIGHT)) $criteria->add(wfCRMPeer::TREE_RIGHT, $this->tree_right);
		if ($this->isColumnModified(wfCRMPeer::PARENT_NODE_ID)) $criteria->add(wfCRMPeer::PARENT_NODE_ID, $this->parent_node_id);
		if ($this->isColumnModified(wfCRMPeer::TREE_ID)) $criteria->add(wfCRMPeer::TREE_ID, $this->tree_id);
		if ($this->isColumnModified(wfCRMPeer::DEPARTMENT_NAME)) $criteria->add(wfCRMPeer::DEPARTMENT_NAME, $this->department_name);
		if ($this->isColumnModified(wfCRMPeer::FIRST_NAME)) $criteria->add(wfCRMPeer::FIRST_NAME, $this->first_name);
		if ($this->isColumnModified(wfCRMPeer::MIDDLE_NAME)) $criteria->add(wfCRMPeer::MIDDLE_NAME, $this->middle_name);
		if ($this->isColumnModified(wfCRMPeer::LAST_NAME)) $criteria->add(wfCRMPeer::LAST_NAME, $this->last_name);
		if ($this->isColumnModified(wfCRMPeer::SALUTATION)) $criteria->add(wfCRMPeer::SALUTATION, $this->salutation);
		if ($this->isColumnModified(wfCRMPeer::TITLES)) $criteria->add(wfCRMPeer::TITLES, $this->titles);
		if ($this->isColumnModified(wfCRMPeer::JOB_TITLE)) $criteria->add(wfCRMPeer::JOB_TITLE, $this->job_title);
		if ($this->isColumnModified(wfCRMPeer::ALPHA_NAME)) $criteria->add(wfCRMPeer::ALPHA_NAME, $this->alpha_name);
		if ($this->isColumnModified(wfCRMPeer::EMAIL)) $criteria->add(wfCRMPeer::EMAIL, $this->email);
		if ($this->isColumnModified(wfCRMPeer::WORK_PHONE)) $criteria->add(wfCRMPeer::WORK_PHONE, $this->work_phone);
		if ($this->isColumnModified(wfCRMPeer::MOBILE_PHONE)) $criteria->add(wfCRMPeer::MOBILE_PHONE, $this->mobile_phone);
		if ($this->isColumnModified(wfCRMPeer::HOME_PHONE)) $criteria->add(wfCRMPeer::HOME_PHONE, $this->home_phone);
		if ($this->isColumnModified(wfCRMPeer::FAX)) $criteria->add(wfCRMPeer::FAX, $this->fax);
		if ($this->isColumnModified(wfCRMPeer::HOMEPAGE)) $criteria->add(wfCRMPeer::HOMEPAGE, $this->homepage);
		if ($this->isColumnModified(wfCRMPeer::PRIVATE_NOTES)) $criteria->add(wfCRMPeer::PRIVATE_NOTES, $this->private_notes);
		if ($this->isColumnModified(wfCRMPeer::PUBLIC_NOTES)) $criteria->add(wfCRMPeer::PUBLIC_NOTES, $this->public_notes);
		if ($this->isColumnModified(wfCRMPeer::IS_COMPANY)) $criteria->add(wfCRMPeer::IS_COMPANY, $this->is_company);
		if ($this->isColumnModified(wfCRMPeer::IS_IN_ADDRESSBOOK)) $criteria->add(wfCRMPeer::IS_IN_ADDRESSBOOK, $this->is_in_addressbook);
		if ($this->isColumnModified(wfCRMPeer::CREATED_AT)) $criteria->add(wfCRMPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(wfCRMPeer::UPDATED_AT)) $criteria->add(wfCRMPeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);

		$criteria->add(wfCRMPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of wfCRM (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setTreeLeft($this->tree_left);

		$copyObj->setTreeRight($this->tree_right);

		$copyObj->setParentNodeId($this->parent_node_id);

		$copyObj->setTreeId($this->tree_id);

		$copyObj->setDepartmentName($this->department_name);

		$copyObj->setFirstName($this->first_name);

		$copyObj->setMiddleName($this->middle_name);

		$copyObj->setLastName($this->last_name);

		$copyObj->setSalutation($this->salutation);

		$copyObj->setTitles($this->titles);

		$copyObj->setJobTitle($this->job_title);

		$copyObj->setAlphaName($this->alpha_name);

		$copyObj->setEmail($this->email);

		$copyObj->setWorkPhone($this->work_phone);

		$copyObj->setMobilePhone($this->mobile_phone);

		$copyObj->setHomePhone($this->home_phone);

		$copyObj->setFax($this->fax);

		$copyObj->setHomepage($this->homepage);

		$copyObj->setPrivateNotes($this->private_notes);

		$copyObj->setPublicNotes($this->public_notes);

		$copyObj->setIsCompany($this->is_company);

		$copyObj->setIsInAddressbook($this->is_in_addressbook);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getSuppliers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSupplier($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getManufacturers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addManufacturer($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getEmployees() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addEmployee($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomer($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getwfCRMsRelatedByParentNodeId() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addwfCRMRelatedByParentNodeId($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getwfCRMCategoryRefs() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addwfCRMCategoryRef($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getwfCRMAddresss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addwfCRMAddress($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getwfCRMCorrespondences() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addwfCRMCorrespondence($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     wfCRM Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     wfCRMPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new wfCRMPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a wfCRM object.
	 *
	 * @param      wfCRM $v
	 * @return     wfCRM The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setwfCRMRelatedByParentNodeId(wfCRM $v = null)
	{
		if ($v === null) {
			$this->setParentNodeId(NULL);
		} else {
			$this->setParentNodeId($v->getId());
		}

		$this->awfCRMRelatedByParentNodeId = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the wfCRM object, it will not be re-added.
		if ($v !== null) {
			$v->addwfCRMRelatedByParentNodeId($this);
		}

		return $this;
	}


	/**
	 * Get the associated wfCRM object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     wfCRM The associated wfCRM object.
	 * @throws     PropelException
	 */
	public function getwfCRMRelatedByParentNodeId(PropelPDO $con = null)
	{
		if ($this->awfCRMRelatedByParentNodeId === null && ($this->parent_node_id !== null)) {
			$c = new Criteria(wfCRMPeer::DATABASE_NAME);
			$c->add(wfCRMPeer::ID, $this->parent_node_id);
			$this->awfCRMRelatedByParentNodeId = wfCRMPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->awfCRMRelatedByParentNodeId->addwfCRMsRelatedByParentNodeId($this);
			 */
		}
		return $this->awfCRMRelatedByParentNodeId;
	}

	/**
	 * Clears out the collSuppliers collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSuppliers()
	 */
	public function clearSuppliers()
	{
		$this->collSuppliers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSuppliers collection (array).
	 *
	 * By default this just sets the collSuppliers collection to an empty array (like clearcollSuppliers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSuppliers()
	{
		$this->collSuppliers = array();
	}

	/**
	 * Gets an array of Supplier objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related Suppliers from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Supplier[]
	 * @throws     PropelException
	 */
	public function getSuppliers($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSuppliers === null) {
			if ($this->isNew()) {
			   $this->collSuppliers = array();
			} else {

				$criteria->add(SupplierPeer::WF_CRM_ID, $this->id);

				SupplierPeer::addSelectColumns($criteria);
				$this->collSuppliers = SupplierPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SupplierPeer::WF_CRM_ID, $this->id);

				SupplierPeer::addSelectColumns($criteria);
				if (!isset($this->lastSupplierCriteria) || !$this->lastSupplierCriteria->equals($criteria)) {
					$this->collSuppliers = SupplierPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSupplierCriteria = $criteria;
		return $this->collSuppliers;
	}

	/**
	 * Returns the number of related Supplier objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Supplier objects.
	 * @throws     PropelException
	 */
	public function countSuppliers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSuppliers === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SupplierPeer::WF_CRM_ID, $this->id);

				$count = SupplierPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SupplierPeer::WF_CRM_ID, $this->id);

				if (!isset($this->lastSupplierCriteria) || !$this->lastSupplierCriteria->equals($criteria)) {
					$count = SupplierPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSuppliers);
				}
			} else {
				$count = count($this->collSuppliers);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Supplier object to this object
	 * through the Supplier foreign key attribute.
	 *
	 * @param      Supplier $l Supplier
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSupplier(Supplier $l)
	{
		if ($this->collSuppliers === null) {
			$this->initSuppliers();
		}
		if (!in_array($l, $this->collSuppliers, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSuppliers, $l);
			$l->setwfCRM($this);
		}
	}

	/**
	 * Clears out the collManufacturers collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addManufacturers()
	 */
	public function clearManufacturers()
	{
		$this->collManufacturers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collManufacturers collection (array).
	 *
	 * By default this just sets the collManufacturers collection to an empty array (like clearcollManufacturers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initManufacturers()
	{
		$this->collManufacturers = array();
	}

	/**
	 * Gets an array of Manufacturer objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related Manufacturers from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Manufacturer[]
	 * @throws     PropelException
	 */
	public function getManufacturers($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collManufacturers === null) {
			if ($this->isNew()) {
			   $this->collManufacturers = array();
			} else {

				$criteria->add(ManufacturerPeer::WF_CRM_ID, $this->id);

				ManufacturerPeer::addSelectColumns($criteria);
				$this->collManufacturers = ManufacturerPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ManufacturerPeer::WF_CRM_ID, $this->id);

				ManufacturerPeer::addSelectColumns($criteria);
				if (!isset($this->lastManufacturerCriteria) || !$this->lastManufacturerCriteria->equals($criteria)) {
					$this->collManufacturers = ManufacturerPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastManufacturerCriteria = $criteria;
		return $this->collManufacturers;
	}

	/**
	 * Returns the number of related Manufacturer objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Manufacturer objects.
	 * @throws     PropelException
	 */
	public function countManufacturers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collManufacturers === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ManufacturerPeer::WF_CRM_ID, $this->id);

				$count = ManufacturerPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ManufacturerPeer::WF_CRM_ID, $this->id);

				if (!isset($this->lastManufacturerCriteria) || !$this->lastManufacturerCriteria->equals($criteria)) {
					$count = ManufacturerPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collManufacturers);
				}
			} else {
				$count = count($this->collManufacturers);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Manufacturer object to this object
	 * through the Manufacturer foreign key attribute.
	 *
	 * @param      Manufacturer $l Manufacturer
	 * @return     void
	 * @throws     PropelException
	 */
	public function addManufacturer(Manufacturer $l)
	{
		if ($this->collManufacturers === null) {
			$this->initManufacturers();
		}
		if (!in_array($l, $this->collManufacturers, true)) { // only add it if the **same** object is not already associated
			array_push($this->collManufacturers, $l);
			$l->setwfCRM($this);
		}
	}

	/**
	 * Clears out the collEmployees collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addEmployees()
	 */
	public function clearEmployees()
	{
		$this->collEmployees = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collEmployees collection (array).
	 *
	 * By default this just sets the collEmployees collection to an empty array (like clearcollEmployees());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initEmployees()
	{
		$this->collEmployees = array();
	}

	/**
	 * Gets an array of Employee objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related Employees from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Employee[]
	 * @throws     PropelException
	 */
	public function getEmployees($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEmployees === null) {
			if ($this->isNew()) {
			   $this->collEmployees = array();
			} else {

				$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

				EmployeePeer::addSelectColumns($criteria);
				$this->collEmployees = EmployeePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

				EmployeePeer::addSelectColumns($criteria);
				if (!isset($this->lastEmployeeCriteria) || !$this->lastEmployeeCriteria->equals($criteria)) {
					$this->collEmployees = EmployeePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastEmployeeCriteria = $criteria;
		return $this->collEmployees;
	}

	/**
	 * Returns the number of related Employee objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Employee objects.
	 * @throws     PropelException
	 */
	public function countEmployees(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collEmployees === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

				$count = EmployeePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

				if (!isset($this->lastEmployeeCriteria) || !$this->lastEmployeeCriteria->equals($criteria)) {
					$count = EmployeePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collEmployees);
				}
			} else {
				$count = count($this->collEmployees);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Employee object to this object
	 * through the Employee foreign key attribute.
	 *
	 * @param      Employee $l Employee
	 * @return     void
	 * @throws     PropelException
	 */
	public function addEmployee(Employee $l)
	{
		if ($this->collEmployees === null) {
			$this->initEmployees();
		}
		if (!in_array($l, $this->collEmployees, true)) { // only add it if the **same** object is not already associated
			array_push($this->collEmployees, $l);
			$l->setwfCRM($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this wfCRM is new, it will return
	 * an empty collection; or if this wfCRM has previously
	 * been saved, it will retrieve related Employees from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in wfCRM.
	 */
	public function getEmployeesJoinsfGuardUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEmployees === null) {
			if ($this->isNew()) {
				$this->collEmployees = array();
			} else {

				$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

				$this->collEmployees = EmployeePeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(EmployeePeer::WF_CRM_ID, $this->id);

			if (!isset($this->lastEmployeeCriteria) || !$this->lastEmployeeCriteria->equals($criteria)) {
				$this->collEmployees = EmployeePeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastEmployeeCriteria = $criteria;

		return $this->collEmployees;
	}

	/**
	 * Clears out the collCustomers collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomers()
	 */
	public function clearCustomers()
	{
		$this->collCustomers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomers collection (array).
	 *
	 * By default this just sets the collCustomers collection to an empty array (like clearcollCustomers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomers()
	{
		$this->collCustomers = array();
	}

	/**
	 * Gets an array of Customer objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related Customers from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Customer[]
	 * @throws     PropelException
	 */
	public function getCustomers($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomers === null) {
			if ($this->isNew()) {
			   $this->collCustomers = array();
			} else {

				$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

				CustomerPeer::addSelectColumns($criteria);
				$this->collCustomers = CustomerPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

				CustomerPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerCriteria) || !$this->lastCustomerCriteria->equals($criteria)) {
					$this->collCustomers = CustomerPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerCriteria = $criteria;
		return $this->collCustomers;
	}

	/**
	 * Returns the number of related Customer objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Customer objects.
	 * @throws     PropelException
	 */
	public function countCustomers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomers === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

				$count = CustomerPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

				if (!isset($this->lastCustomerCriteria) || !$this->lastCustomerCriteria->equals($criteria)) {
					$count = CustomerPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomers);
				}
			} else {
				$count = count($this->collCustomers);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Customer object to this object
	 * through the Customer foreign key attribute.
	 *
	 * @param      Customer $l Customer
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomer(Customer $l)
	{
		if ($this->collCustomers === null) {
			$this->initCustomers();
		}
		if (!in_array($l, $this->collCustomers, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomers, $l);
			$l->setwfCRM($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this wfCRM is new, it will return
	 * an empty collection; or if this wfCRM has previously
	 * been saved, it will retrieve related Customers from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in wfCRM.
	 */
	public function getCustomersJoinsfGuardUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomers === null) {
			if ($this->isNew()) {
				$this->collCustomers = array();
			} else {

				$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

				$this->collCustomers = CustomerPeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerPeer::WF_CRM_ID, $this->id);

			if (!isset($this->lastCustomerCriteria) || !$this->lastCustomerCriteria->equals($criteria)) {
				$this->collCustomers = CustomerPeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerCriteria = $criteria;

		return $this->collCustomers;
	}

	/**
	 * Clears out the collwfCRMsRelatedByParentNodeId collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addwfCRMsRelatedByParentNodeId()
	 */
	public function clearwfCRMsRelatedByParentNodeId()
	{
		$this->collwfCRMsRelatedByParentNodeId = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collwfCRMsRelatedByParentNodeId collection (array).
	 *
	 * By default this just sets the collwfCRMsRelatedByParentNodeId collection to an empty array (like clearcollwfCRMsRelatedByParentNodeId());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initwfCRMsRelatedByParentNodeId()
	{
		$this->collwfCRMsRelatedByParentNodeId = array();
	}

	/**
	 * Gets an array of wfCRM objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related wfCRMsRelatedByParentNodeId from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array wfCRM[]
	 * @throws     PropelException
	 */
	public function getwfCRMsRelatedByParentNodeId($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collwfCRMsRelatedByParentNodeId === null) {
			if ($this->isNew()) {
			   $this->collwfCRMsRelatedByParentNodeId = array();
			} else {

				$criteria->add(wfCRMPeer::PARENT_NODE_ID, $this->id);

				wfCRMPeer::addSelectColumns($criteria);
				$this->collwfCRMsRelatedByParentNodeId = wfCRMPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(wfCRMPeer::PARENT_NODE_ID, $this->id);

				wfCRMPeer::addSelectColumns($criteria);
				if (!isset($this->lastwfCRMRelatedByParentNodeIdCriteria) || !$this->lastwfCRMRelatedByParentNodeIdCriteria->equals($criteria)) {
					$this->collwfCRMsRelatedByParentNodeId = wfCRMPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastwfCRMRelatedByParentNodeIdCriteria = $criteria;
		return $this->collwfCRMsRelatedByParentNodeId;
	}

	/**
	 * Returns the number of related wfCRM objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related wfCRM objects.
	 * @throws     PropelException
	 */
	public function countwfCRMsRelatedByParentNodeId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collwfCRMsRelatedByParentNodeId === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(wfCRMPeer::PARENT_NODE_ID, $this->id);

				$count = wfCRMPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(wfCRMPeer::PARENT_NODE_ID, $this->id);

				if (!isset($this->lastwfCRMRelatedByParentNodeIdCriteria) || !$this->lastwfCRMRelatedByParentNodeIdCriteria->equals($criteria)) {
					$count = wfCRMPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collwfCRMsRelatedByParentNodeId);
				}
			} else {
				$count = count($this->collwfCRMsRelatedByParentNodeId);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a wfCRM object to this object
	 * through the wfCRM foreign key attribute.
	 *
	 * @param      wfCRM $l wfCRM
	 * @return     void
	 * @throws     PropelException
	 */
	public function addwfCRMRelatedByParentNodeId(wfCRM $l)
	{
		if ($this->collwfCRMsRelatedByParentNodeId === null) {
			$this->initwfCRMsRelatedByParentNodeId();
		}
		if (!in_array($l, $this->collwfCRMsRelatedByParentNodeId, true)) { // only add it if the **same** object is not already associated
			array_push($this->collwfCRMsRelatedByParentNodeId, $l);
			$l->setwfCRMRelatedByParentNodeId($this);
		}
	}

	/**
	 * Clears out the collwfCRMCategoryRefs collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addwfCRMCategoryRefs()
	 */
	public function clearwfCRMCategoryRefs()
	{
		$this->collwfCRMCategoryRefs = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collwfCRMCategoryRefs collection (array).
	 *
	 * By default this just sets the collwfCRMCategoryRefs collection to an empty array (like clearcollwfCRMCategoryRefs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initwfCRMCategoryRefs()
	{
		$this->collwfCRMCategoryRefs = array();
	}

	/**
	 * Gets an array of wfCRMCategoryRef objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related wfCRMCategoryRefs from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array wfCRMCategoryRef[]
	 * @throws     PropelException
	 */
	public function getwfCRMCategoryRefs($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collwfCRMCategoryRefs === null) {
			if ($this->isNew()) {
			   $this->collwfCRMCategoryRefs = array();
			} else {

				$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

				wfCRMCategoryRefPeer::addSelectColumns($criteria);
				$this->collwfCRMCategoryRefs = wfCRMCategoryRefPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

				wfCRMCategoryRefPeer::addSelectColumns($criteria);
				if (!isset($this->lastwfCRMCategoryRefCriteria) || !$this->lastwfCRMCategoryRefCriteria->equals($criteria)) {
					$this->collwfCRMCategoryRefs = wfCRMCategoryRefPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastwfCRMCategoryRefCriteria = $criteria;
		return $this->collwfCRMCategoryRefs;
	}

	/**
	 * Returns the number of related wfCRMCategoryRef objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related wfCRMCategoryRef objects.
	 * @throws     PropelException
	 */
	public function countwfCRMCategoryRefs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collwfCRMCategoryRefs === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

				$count = wfCRMCategoryRefPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

				if (!isset($this->lastwfCRMCategoryRefCriteria) || !$this->lastwfCRMCategoryRefCriteria->equals($criteria)) {
					$count = wfCRMCategoryRefPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collwfCRMCategoryRefs);
				}
			} else {
				$count = count($this->collwfCRMCategoryRefs);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a wfCRMCategoryRef object to this object
	 * through the wfCRMCategoryRef foreign key attribute.
	 *
	 * @param      wfCRMCategoryRef $l wfCRMCategoryRef
	 * @return     void
	 * @throws     PropelException
	 */
	public function addwfCRMCategoryRef(wfCRMCategoryRef $l)
	{
		if ($this->collwfCRMCategoryRefs === null) {
			$this->initwfCRMCategoryRefs();
		}
		if (!in_array($l, $this->collwfCRMCategoryRefs, true)) { // only add it if the **same** object is not already associated
			array_push($this->collwfCRMCategoryRefs, $l);
			$l->setwfCRM($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this wfCRM is new, it will return
	 * an empty collection; or if this wfCRM has previously
	 * been saved, it will retrieve related wfCRMCategoryRefs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in wfCRM.
	 */
	public function getwfCRMCategoryRefsJoinwfCRMCategory($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collwfCRMCategoryRefs === null) {
			if ($this->isNew()) {
				$this->collwfCRMCategoryRefs = array();
			} else {

				$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

				$this->collwfCRMCategoryRefs = wfCRMCategoryRefPeer::doSelectJoinwfCRMCategory($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(wfCRMCategoryRefPeer::CRM_ID, $this->id);

			if (!isset($this->lastwfCRMCategoryRefCriteria) || !$this->lastwfCRMCategoryRefCriteria->equals($criteria)) {
				$this->collwfCRMCategoryRefs = wfCRMCategoryRefPeer::doSelectJoinwfCRMCategory($criteria, $con, $join_behavior);
			}
		}
		$this->lastwfCRMCategoryRefCriteria = $criteria;

		return $this->collwfCRMCategoryRefs;
	}

	/**
	 * Clears out the collwfCRMAddresss collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addwfCRMAddresss()
	 */
	public function clearwfCRMAddresss()
	{
		$this->collwfCRMAddresss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collwfCRMAddresss collection (array).
	 *
	 * By default this just sets the collwfCRMAddresss collection to an empty array (like clearcollwfCRMAddresss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initwfCRMAddresss()
	{
		$this->collwfCRMAddresss = array();
	}

	/**
	 * Gets an array of wfCRMAddress objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related wfCRMAddresss from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array wfCRMAddress[]
	 * @throws     PropelException
	 */
	public function getwfCRMAddresss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collwfCRMAddresss === null) {
			if ($this->isNew()) {
			   $this->collwfCRMAddresss = array();
			} else {

				$criteria->add(wfCRMAddressPeer::CRM_ID, $this->id);

				wfCRMAddressPeer::addSelectColumns($criteria);
				$this->collwfCRMAddresss = wfCRMAddressPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(wfCRMAddressPeer::CRM_ID, $this->id);

				wfCRMAddressPeer::addSelectColumns($criteria);
				if (!isset($this->lastwfCRMAddressCriteria) || !$this->lastwfCRMAddressCriteria->equals($criteria)) {
					$this->collwfCRMAddresss = wfCRMAddressPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastwfCRMAddressCriteria = $criteria;
		return $this->collwfCRMAddresss;
	}

	/**
	 * Returns the number of related wfCRMAddress objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related wfCRMAddress objects.
	 * @throws     PropelException
	 */
	public function countwfCRMAddresss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collwfCRMAddresss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(wfCRMAddressPeer::CRM_ID, $this->id);

				$count = wfCRMAddressPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(wfCRMAddressPeer::CRM_ID, $this->id);

				if (!isset($this->lastwfCRMAddressCriteria) || !$this->lastwfCRMAddressCriteria->equals($criteria)) {
					$count = wfCRMAddressPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collwfCRMAddresss);
				}
			} else {
				$count = count($this->collwfCRMAddresss);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a wfCRMAddress object to this object
	 * through the wfCRMAddress foreign key attribute.
	 *
	 * @param      wfCRMAddress $l wfCRMAddress
	 * @return     void
	 * @throws     PropelException
	 */
	public function addwfCRMAddress(wfCRMAddress $l)
	{
		if ($this->collwfCRMAddresss === null) {
			$this->initwfCRMAddresss();
		}
		if (!in_array($l, $this->collwfCRMAddresss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collwfCRMAddresss, $l);
			$l->setwfCRM($this);
		}
	}

	/**
	 * Clears out the collwfCRMCorrespondences collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addwfCRMCorrespondences()
	 */
	public function clearwfCRMCorrespondences()
	{
		$this->collwfCRMCorrespondences = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collwfCRMCorrespondences collection (array).
	 *
	 * By default this just sets the collwfCRMCorrespondences collection to an empty array (like clearcollwfCRMCorrespondences());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initwfCRMCorrespondences()
	{
		$this->collwfCRMCorrespondences = array();
	}

	/**
	 * Gets an array of wfCRMCorrespondence objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this wfCRM has previously been saved, it will retrieve
	 * related wfCRMCorrespondences from storage. If this wfCRM is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array wfCRMCorrespondence[]
	 * @throws     PropelException
	 */
	public function getwfCRMCorrespondences($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collwfCRMCorrespondences === null) {
			if ($this->isNew()) {
			   $this->collwfCRMCorrespondences = array();
			} else {

				$criteria->add(wfCRMCorrespondencePeer::WF_CRM_ID, $this->id);

				wfCRMCorrespondencePeer::addSelectColumns($criteria);
				$this->collwfCRMCorrespondences = wfCRMCorrespondencePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(wfCRMCorrespondencePeer::WF_CRM_ID, $this->id);

				wfCRMCorrespondencePeer::addSelectColumns($criteria);
				if (!isset($this->lastwfCRMCorrespondenceCriteria) || !$this->lastwfCRMCorrespondenceCriteria->equals($criteria)) {
					$this->collwfCRMCorrespondences = wfCRMCorrespondencePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastwfCRMCorrespondenceCriteria = $criteria;
		return $this->collwfCRMCorrespondences;
	}

	/**
	 * Returns the number of related wfCRMCorrespondence objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related wfCRMCorrespondence objects.
	 * @throws     PropelException
	 */
	public function countwfCRMCorrespondences(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(wfCRMPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collwfCRMCorrespondences === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(wfCRMCorrespondencePeer::WF_CRM_ID, $this->id);

				$count = wfCRMCorrespondencePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(wfCRMCorrespondencePeer::WF_CRM_ID, $this->id);

				if (!isset($this->lastwfCRMCorrespondenceCriteria) || !$this->lastwfCRMCorrespondenceCriteria->equals($criteria)) {
					$count = wfCRMCorrespondencePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collwfCRMCorrespondences);
				}
			} else {
				$count = count($this->collwfCRMCorrespondences);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a wfCRMCorrespondence object to this object
	 * through the wfCRMCorrespondence foreign key attribute.
	 *
	 * @param      wfCRMCorrespondence $l wfCRMCorrespondence
	 * @return     void
	 * @throws     PropelException
	 */
	public function addwfCRMCorrespondence(wfCRMCorrespondence $l)
	{
		if ($this->collwfCRMCorrespondences === null) {
			$this->initwfCRMCorrespondences();
		}
		if (!in_array($l, $this->collwfCRMCorrespondences, true)) { // only add it if the **same** object is not already associated
			array_push($this->collwfCRMCorrespondences, $l);
			$l->setwfCRM($this);
		}
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collSuppliers) {
				foreach ((array) $this->collSuppliers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collManufacturers) {
				foreach ((array) $this->collManufacturers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collEmployees) {
				foreach ((array) $this->collEmployees as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomers) {
				foreach ((array) $this->collCustomers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collwfCRMsRelatedByParentNodeId) {
				foreach ((array) $this->collwfCRMsRelatedByParentNodeId as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collwfCRMCategoryRefs) {
				foreach ((array) $this->collwfCRMCategoryRefs as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collwfCRMAddresss) {
				foreach ((array) $this->collwfCRMAddresss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collwfCRMCorrespondences) {
				foreach ((array) $this->collwfCRMCorrespondences as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collSuppliers = null;
		$this->collManufacturers = null;
		$this->collEmployees = null;
		$this->collCustomers = null;
		$this->collwfCRMsRelatedByParentNodeId = null;
		$this->collwfCRMCategoryRefs = null;
		$this->collwfCRMAddresss = null;
		$this->collwfCRMCorrespondences = null;
			$this->awfCRMRelatedByParentNodeId = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasewfCRM:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasewfCRM::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasewfCRM

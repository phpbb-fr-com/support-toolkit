<?php
/**
 *
 * @package SupportToolkit
 * @copyright (c) 2012 phpBB Group
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * Support Toolkit tool
 *
 * An wrapper for STK tools, provides various common methods and holds the
 * actual tool object.
 */
class stk_toolbox_tool implements Serializable
{
	private $active;
	private $category;
	private $id;
	private $loadError;
	private $outdated;
	private $stk;
	private $tool;

	/**
	 * Initialise tool object
	 *
	 * This is a wrapper object that contains a tool for the support toolkit,
	 * this method should be called when initialising a new tool. It handles
	 * some initial validation on the tool and returns the `stk_toolbox_tool`
	 * object for the requested tool
	 *
	 * @param stk_core_version_controller $vc
	 * @return string|\static The `stk_toolbox_tool` object or a string when an
	 *                        error occured.
	 */
	public function __construct(Pimple $stk = null)
	{
		$this->active		= false;
		$this->loadError	= '';
		$this->outdated		= false;
		$this->stk			= $stk;
		$this->tool			= null;
	}

	/**
	 * Validate and load the tool
	 *
	 * Check whether the requested tool is valid and load an instance
	 * of the requested tool into the wrapper
	 */
	public function validateAndLoad()
	{
		$className	= "stktool_{$this->category}_{$this->id}";
		// Test whether the class name is correctly formatted
		if (!preg_match('#^stktool_[a-zA-Z]+_[a-zA-Z_]+$#', $className))
		{
			$this->loadError = 'TOOL_CLASSNAME_WRONG_FORMAT';
			return;
		}

		$rc = new ReflectionClass($className);

		// Must implement the interface
		if (false === ($rc->implementsInterface('stk_toolbox_toolInterface')))
		{
			$this->loadError = 'TOOL_CLASS_NOT_IMPLEMENTS_INTERFACE';
			return false;
		}

		// Tool version check
		$vcr = $this->stk['vc']->testToolVersion($this->category, $this->id);
		if ($vcr == stk_core_version_controller::VERSION_BLOCKING || $vcr == stk_core_version_controller::VERSION_DISABLED)
		{
			$this->loadError = ($vcr == stk_core_version_controller::VERSION_BLOCKING) ? 'TOOL_VERSION_BLOCKED' : 'VERSION_DISABLED';
			return false;
		}
		$this->outdated	= ($vcr != stk_core_version_controller::VERSION_OK) ? true : false;
		$this->tool		= $rc->newInstance($this->stk);

		return true;
	}

	/**
	 * Create overview
	 *
	 * Create the overview page for the contained tool
	 */
	public function createOverview()
	{
		if (is_null($this->tool))
		{
			$this->validateAndLoad();
		}

		// Make sure the language file is loaded
		$this->loadToolLanguageFile();

		// Show some normal information, tool title and description
		$this->stk['phpbb']['template']->assign_vars(array(
			'L_TOOL_TITLE'			=> $this->stk['phpbb']['user']->lang(strtoupper($this->id . '_TITLE')),
			'L_TOOL_DESCRIPTION'	=> $this->stk['phpbb']['user']->lang(strtoupper($this->id . '_DESCRIPTION')),
		));

		$options = $this->tool->displayOptions();

		// Show outdated notice
		if ($this->outdated)
		{
			$this->stk['phpbb']['template']->addNotice('TOOL_OUTDATED_TITLE', 'TOOL_OUTDATED_DESCRIPTION');
		}

		// Various options
		if ($options === TOOL_OVERVIEW_TRIGGER)
		{
			$this->createStringOverview();
		}
	}

	/**
	 * Run the requested tool
	 */
	public function runTool()
	{
		if (is_null($this->tool))
		{
			$this->validateAndLoad();
		}

		if ($this->tool->run() === true)
		{
			$this->stk['phpbb']['template']->assign_vars(array(
				'L_TOOL_TITLE'		=> $this->stk['phpbb']['user']->lang(strtoupper($this->id . '_TITLE')),
				'L_TOOL_SUCCESS'	=> $this->stk['phpbb']['user']->lang(strtoupper($this->id . '_SUCCESS')),
			));

			$this->stk['utilities']->page_header('TOOL_SUCCESS');
			$this->stk['utilities']->page_footer('tool_success');
		}
		else
		{
			throw new RuntimeException('Not yet implemented');
		}
	}

	/**
	 * Create "trigger overview"
	 *
	 * The overview page of the requested tool only consists of a
	 * confirmbox. Generate and handle the confirm box
	 */
	private function createStringOverview()
	{
		$displayHandler = new stk_toolbox_display_trigger($this);

		if ($displayHandler->isConfirmed())
		{
			$this->runTool();
		}
		else
		{
			$displayHandler->setNotice(strtoupper($this->id));
			$displayHandler->display();
		}
	}

	/**
	 * Load the main language file for this tool
	 */
	public function loadToolLanguageFile()
	{
		$this->stk['phpbb']['user']->stk_add_lang("tools/{$this->category}/{$this->id}");
	}

	/**
	 * Tool is active
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * Switch tool active
	 */
	public function setActive($active = false)
	{
		$this->active = $active;
	}

	/**
	 * Get the ID of this tool
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * Get load error
	 *
	 * The error string of the error that occured during the validation
	 * of the requested tool
	 */
	public function getLoadError()
	{
		return $this->loadError;
	}

	/**
	 * Get tool language string
	 *
	 * Create the language entry that is used to identify this tool
	 * in the UI
	 */
	public function getToolLanguageString()
	{
		return strtoupper("TOOL_{$this->category}_{$this->id}");
	}

	/**
	 * Get the tool
	 */
	public function getTool()
	{
		return $this->tool;
	}

	/**
	 * Get tool URL
	 *
	 * Create the URL pointing to this tool
	 *
	 * @param  array  $params Addtional parameters to be added to the url
	 * @return string         The URL
	 */
	public function getToolURL(array $params = array())
	{
		// Add cat/tool to the params
		$params['c'] = $this->category;
		$params['t'] = $this->id;

		return append_sid(STK_WEB_PATH . '/index.php', $params);
	}

	/**
	 * Set the path to this tool file
	 *
	 * @param SplFileInfo $path Path to the tool file, the correct class name is
	 *                          determined from here
	 */
	public function setPath(SplFileInfo $path)
	{
		$this->category	= substr(strrchr($path->getPath(), '/'), 1);
		$this->id		= $path->getBasename('.php');
	}

	/**
	 * Set the DI container for this tool wrapper
	 *
	 * @param Pimple $container The container
	 */
	public function setDIContainer(Pimple $container)
	{
		$this->stk = $container;
	}

	/**
	 * Serialize this category for storage
	 *
	 * @return string
	 */
	public function serialize()
	{
		$data = array(
			'active'	=> $this->active,
			'category'	=> $this->category,
			'id'		=> $this->id,
			'loadError'	=> $this->loadError,
			'outdated'	=> $this->outdated,
			'tool'		=> $this->tool,
		);

		return serialize($data);
	}

	/**
	 * Unserialize this catogry when retrieved from storage
	 *
	 * @param string $serialized
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		$this->active		= $data['active'];
		$this->category		= $data['category'];
		$this->id			= $data['id'];
		$this->loadError	= $data['loadError'];
		$this->outdated		= $data['outdated'];
		$this->tool			= $data['tool'];
	}
}

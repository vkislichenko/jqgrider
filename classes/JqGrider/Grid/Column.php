<?php
/**
* JqGrider library
*
* @package    nMind\jqGrider
* @version    1.0.0
* @license    MIT License
* @copyright  2012 Ivan Đurđevac
*/

namespace JqGrider\Grid;

class Column
{
	const ALIGN_LEFT = 'left';
	
	const ALIGN_RIGHT = 'right';
	
	const ALIGN_CENTER = 'center';
	
	
	/**
	 * 
	 * Title for frontend
	 * @var string
	 */
	protected $title;

	/**
	 * 
	 * Repository attribute name
	 * @var string
	 */
	protected $repositoryAttribute;
	
	/**
	 * 
	 * Column width
	 * @var int
	 */
	protected $width;
	
	/**
	 * 
	 * Column text alignment
	 * Align left is default
	 * @var string
	 */
	protected $align = self::ALIGN_LEFT;
	
	/**
	 * Callback function
	 * @var Anonymous function
	 */
	protected $callbackFunction;

    protected $searchOptions;

    /**
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @param string $align
     * @return Column
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    protected $searchType;

    /**
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * @param string $searchType
     * @return Column
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSearchOptions()
    {
        return $this->searchOptions;
    }

    /**
     * @param boolean $searchOptions
     * @return Column
     */
    public function setSearchOptions($searchOptions)
    {
        $this->searchOptions = $searchOptions;
        return $this;
    }
	/**
	 * 
	 * Constructor
	 * 
	 * @param string $title
	 * @param string $repositoryAttribute
	 * @param int $width
	 * @param mixed $callbackFunction
	 */
	public function __construct(array $config)
	{
	    $config = array_merge([
            'title' => null,
            'repositoryAttribute' => null,
            'width' => null,
            'callbackFunction' => null,
            'searchType' => 'text',
            'searchOptions' => null,
        ],$config);

	    foreach($config as $k => $v) {
	        $this->{$k} = $v;
        }
	}
	
	/**
	 * Get anonymous function for callback
	 */
	public function getCallbackFunction()
	{
		return $this->callbackFunction;
	}
	
	/**
	 * Get column title
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Get respository attribute
	 * In most cases db table column name
	 */
	public function getRepositoryAttribute()
	{
		return $this->repositoryAttribute;
	}
	
	/**
	 * Get column width
	 */
	public function getWidth()
	{
		return $this->width;
	}
	
}
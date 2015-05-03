<?php
/**
 * JqGrider library
 *
 * @package    nMind\jqGrider
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2012 Ivan Đurđevac
 */


namespace JqGrider;

use JqGrider\Data\RepositoryResult;
use JqGrider\Data\Conditions;
use JqGrider\Data\IGridRepository;
use JqGrider\Grid\Column;
use JqGrider\Grid\ColumnCollection;
use Zend\Json\Json;
class Grid
{
	const DATA_TYPE_XML = 'xml';
	
	const DATA_TYPE_JSON = 'json';
	
	const DATA_TYPE_LOCAL = 'local';
	
	const SORT_ORDER_ASC = 'asc';

	const SORT_ORDER_DESC = 'desc';



	/**
	 *
	 * Data repository
	 *
	 * @var Data\IGridRepository
	 */
	protected $_repository;
	
	
	/**
	 * 
	 * Data Type Strategy Object
	 * @var Data_Type_Abstract
	 */
	protected $_dataTypeStrategy;

    protected $jsonReader;

    /**
     * @return mixed
     */
    public function getJsonReader()
    {
        return $this->jsonReader;
    }

    /**
     * @param mixed $jsonReader
     */
    public function setJsonReader($jsonReader)
    {
        $this->jsonReader = $jsonReader;

        return $this;
    }
	/**
	 *
	 * Columns
	 * @var ColumnCollection
	 */
	protected $columnCollection;

	/**
	 *
	 * Grid caption
	 * @var string
	 */
	protected $caption;

	/**
	 *
	 * Url where grid will find it's data
	 * @var string
	 */
	protected $dataUrl;

	/**
	 *
	 * Data type of url's data
	 * @var string
	 */
	protected $dataType = self::DATA_TYPE_LOCAL;

	/**
	 *
	 * Rows to show per page
	 * @var int
	 */
	protected $rowsPerPage = 10;

	/**
	 *
	 * HTML ID Tag for div where js will generate pager for grid
	 * @var string
	 */
	protected $pagerDivIdentifier = '#pager1';


    protected $gridIdentifier = '#grid1';

    protected $height;

    protected $width;

    /**
     * @return string
     */
    public function getDataUrl()
    {
        return $this->dataUrl;
    }

    /**
     * @param string $dataUrl
     * @return Grid
     */
    public function setDataUrl($dataUrl)
    {
        $this->dataUrl = $dataUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    /**
     * @param int $rowsPerPage
     * @return Grid
     */
    public function setRowsPerPage($rowsPerPage)
    {
        $this->rowsPerPage = $rowsPerPage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return Grid
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return Grid
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isViewRecords()
    {
        return $this->viewRecords;
    }

    /**
     * @param boolean $viewRecords
     * @return Grid
     */
    public function setViewRecords($viewRecords)
    {
        $this->viewRecords = $viewRecords;
        return $this;
    }

    /**
     * @return array
     */
    public function getRowList()
    {
        return $this->rowList;
    }

    /**
     * @param array $rowList
     * @return Grid
     */
    public function setRowList($rowList)
    {
        $this->rowList = $rowList;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     * @return Grid
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLoadOnce()
    {
        return $this->loadOnce;
    }

    /**
     * @param boolean $loadOnce
     * @return Grid
     */
    public function setLoadOnce($loadOnce)
    {
        $this->loadOnce = $loadOnce;
        return $this;
    }
    /**
     * @return string
     */
    public function getGridIdentifier()
    {
        return $this->gridIdentifier;
    }

    /**
     * @param string $gridIdentifier
     */
    public function setGridIdentifier($gridIdentifier)
    {
        $this->gridIdentifier = $gridIdentifier;
        return $this;
    }
    /**
     * @return string
     */
    public function getPagerDivIdentifier()
    {
        return $this->pagerDivIdentifier;
    }

    /**
     * @param string $pagerDivIdentifier
     */
    public function setPagerDivIdentifier($pagerDivIdentifier)
    {
        $this->pagerDivIdentifier = $pagerDivIdentifier;
        return $this;
    }

	/**
	 *
	 * Repositiry attribute used for sort data
	 * @var string
	 */
	protected $sortName = 'id';

	/**
	 *
	 * View records
	 * @var bool
	 */
	protected $viewRecords = true;

	/**
	 *
	 * Row list for dropdown,
	 * where we can pick rows per page
	 *
	 * @var array
	 */
	protected $rowList = array(10, 20, 30);

	/**
	 *
	 * Sort order
	 * @var string
	 */
	protected $sortOrder = self::SORT_ORDER_ASC;
	
	/**
	 * Load once parameter
	 * @var bool
	 */
	protected $loadOnce = false;

	/**
	 * Constructor
	 * 
	 * @param string $dataType
	 * @param array $options
	 */
	public function __construct($dataType, $options = array())
	{
		$this->dataType = $dataType;
		
		$this->columnCollection = new ColumnCollection();
		
		$this->_dataTypeStrategy = Data\Type\Factory::createImplementator($this->dataType);
	}

    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }
	/**
	 *
	 * Add column to Grid
	 * @param string $title
	 * @param string $repositoryAttribute
	 * @param int $width
	 */
	public function addColumn($title, $repositoryAttribute, $width, $callbackFunction = false, $searchOptions)
	{
		$this->columnCollection->add(new Column($title, $repositoryAttribute, $width, $callbackFunction,$searchOptions));

		return $this;
	}

	/**
	 * 
	 * Get json object initialization for grid
	 */
	public function toJson()
	{
		$options = $this->createOptions();
		
		$options = $this->addColumnsToOptions($options);
		
		$options = $this->_dataTypeStrategy->addDetailsToOptions($options, $this);

        return Json::encode(
            $options,
            false,
            array('enableJsonExprFinder' => true)
        );
	}
	
	/**
	 * Get grid javascript code
	 */
	public function getJavaScriptCode()
	{
		$jsonInit = $this->toJson();
        $js = '';
        if(!empty($this->postDataFields)){
            $js = 'var arrPostDataFields = [' . join('","', $this->postDataFields) . ']';
        }
		$js .= <<<JS
    var objPostData={};
    if(arrPostDataFields){
        jQuery.each(arrPostDataFields, function( index, value ) {
          objPostData[value] = jQuery('#'+value).value();
        });
    }
    console.log(arrPostData);
	jQuery("{$this->gridIdentifier}").jqGrid($jsonInit);
	jQuery("{$this->gridIdentifier}").jqGrid('navGrid','$this->pagerDivIdentifier',{edit:false,add:false,del:false});
	jQuery("{$this->gridIdentifier}").jqGrid('filterToolbar','$this->pagerDivIdentifier',{searchOperators : false});
	jQuery("{$this->gridIdentifier}").setGridParam({ postData:  objPostData});


JS;
		if ($moreJs = $this->_dataTypeStrategy->getAditionalJavaScript() and $js .= $moreJs);
		return $js;
	}
	
	/**
	 * 
	 * Template method for additional JS
	 * for clid classes
	 * 
	 * @return string
	 */
	public function getAditionalJavaScript() {
		return;
	}
	
	/**
	 * Create initial options
	 * @return multitype:string
	 */
	private function createOptions()
	{
		return array(
			'datatype' 		=> $this->dataType,
			'caption' 		=> $this->caption,
            'height'        => $this->getHeight()
		);	
	}
	
	/**
	 * Add columns option into option array
	 * @param array $options
	 */
	private function addColumnsToOptions($options)
	{
		$columnNames = array();
		$columnModel = array();
		
		foreach ($this->columnCollection as $column)
		{
			$columnNames[] = $column->getTitle();
            $columnModelAttributes = array(
                'name' 		=> $column->getRepositoryAttribute(),
                'index' 	=> $column->getRepositoryAttribute(),

            );

            if(!empty($column->getSearchOptions())){
                $columnModelAttributes['stype']     = $column->getSearchType()?:'text';
                $columnModelAttributes['searchoptions'] = $column->getSearchOptions();
            }
            $columnModel[] = $columnModelAttributes;
		}


		$options['colNames'] = $columnNames;
		$options['colModel'] = $columnModel;

		return $options;
	}
    /**
     * @param string $sortName
     * @return Grid
     */
    public function setSortName($sortName)
    {
        $this->sortName = $sortName;
        return $this;
    }
	/**
	 * Set respository
	 * 
	 * Respository can be your model or array with data
	 * @param IGridRepository $repository
	 */
	public function setRepository(IGridRepository $repository)
	{
		$this->_repository = $repository;
		
		return $this;
	}
	
	/**
	 * Get respository
	 */
	public function getRespository()
	{
		return $this->_repository;
	}
	
	/**
	 * Get sort attribute name
	 */
	public function getSortName()
	{
		return $this->sortName;
	}
	
	/**
	 * Get columns collection
	 */
	public function getColumnCollection()
	{
		return $this->columnCollection;
	}	
	
	/**
	 * Get data type
	 */
	public function getDataType()
	{
		return $this->dataType;
	}
	
	/**
	 * Set url to grab data for grid with ajax call
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->dataUrl = $url;
		
		return $this;
	}
	
	/**
	 * Print resposiroty data in results format
	 */
	public function printRespositoryData()
	{	
		return RepositoryResult::printData($this);
	}
	
	/**
	 * Magicly call strategy methods over this object
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method, $args)
	{
		if (is_callable(array($this->_dataTypeStrategy, $method)))
		{
			return call_user_func_array(array($this->_dataTypeStrategy, $method), $args);
		}
	}
	
	/**
	 * Magic getter
	 * @param string $field
	 */
	public function __get($field)
	{
		return $this->{$field};
	}
}
 
 
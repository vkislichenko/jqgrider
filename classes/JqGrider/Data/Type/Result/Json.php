<?php
/**
* JqGrider library
*
* @package    nMind\jqGrider
* @version    1.0.0
* @license    MIT License
* @copyright  2012 Ivan Đurđevac
*/

namespace JqGrider\Data\Type\Result;

class Json implements IResultType
{
	/**
	 * (non-PHPdoc)
	 * @see JqGrider\Data\Type.IResultType::conversResource()
	 */
	public function printData($resultResource)
	{
        $resultResource->rows = $this->castToUtf8($resultResource->rows);
		$result = json_encode($resultResource, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		if(empty($result)) {
            $result = json_last_error_msg();
        }
        print $result;
	}

	public function castToUtf8($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
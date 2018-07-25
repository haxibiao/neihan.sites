<?php

namespace App\Helpers;

use App\Model;

/**
 * 字符串工具类
 * 有些特殊的功能PHP和Laravel中没有提供，在此处归纳出来
 */
class StringUtils 
{	 
	/**
	 * @Desc     截取两个字符串之间的内容
	 * @Author   czg
	 * @DateTime 2018-06-21
	 * @param    [type]     $inputString 被截取的字符串
	 * @param    [type]     $startString 起始字符串
	 * @param    [type]     $endString   结束字符串
	 * @return   [type]                  截取的内容
	 */
	public static function getNeedBetween($inputString, $startString, $endString){
		//TODO 考虑是否忽略大小写, 输出判断
	    $p1 = strpos($inputString, $startString) + strlen($startString);
	    $p2 = strpos($inputString, $endString) - $p1;
	    return substr($inputString, $p1, $p2);
	}
}

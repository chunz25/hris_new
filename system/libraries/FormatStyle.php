<?php 
class FormatStyle {
    private static $currentStyles = array();    
    public function __construct() {}
    public function format($string){
		if($string !=""){
			return preg_replace_callback("#<b>|<u>|<i>|</b>|</u>|</i>#", 'FormatStyle::replaceTags', $string);
		}
		else{
			return false;
		}
    }
    private static function applyStyles() {
		$styles ='';
		if(count(self::$currentStyles) > 0){
			foreach(self::$currentStyles as $value){
				if($value == "b"){
					$styles .= "<w:b/>";
				}   
				if($value == "u"){
					$styles .= "<w:u w:val=\"single\"/>";
				}   
				if($value == "i"){
					$styles .= "<w:i/>";
				}
			}
			return "<w:rPr>" . $styles . "</w:rPr>";
		}
		else{
			return false;
		}
    }
    private static function replaceTags($matches){
		if($matches[0] == "<b>"){
			array_push(self::$currentStyles, "b");
		}   
		if($matches[0] == "<u>"){
			array_push(self::$currentStyles, "u");
		}   
		if($matches[0] == "<i>"){
			array_push(self::$currentStyles, "i");
		}
		if($matches[0] == "</b>"){
			self::$currentStyles = array_diff(self::$currentStyles, array("b"));
		}   
		if($matches[0] == "</u>"){
			self::$currentStyles = array_diff(self::$currentStyles, array("u"));
		}   
		if($matches[0] == "</i>"){
			self::$currentStyles = array_diff(self::$currentStyles, array("i"));
		}
		return "</w:t></w:r><w:r>" . self::applyStyles() . "<w:t xml:space=\"preserve\">";
    }
}
?>
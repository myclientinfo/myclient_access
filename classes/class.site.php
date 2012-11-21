<?php
define('BR', '<br />');
define('BR2', '<br /><br />');

class Site{

	var $data;
	var $id;
	var $admin = false;
	var $data_listing = array();
	var $content_type;
	var $table_name;
	var $table_fields;
	var $where;
	
	function __construct($id, $list, $admin = false, $table_name = '', $content_type = '', $where = false){
		if($content_type != '') $this->content_type = $content_type;
		if($table_name != '' && empty($this->table_name))$this->table_name = $table_name;
		if(empty($this->table_fields)) $this->table_fields = $this->getFieldsForType($this->content_type);
		if($admin) $this->setAdmin(1);
		if($id){
			$this->data = $this->getOne($id);
			$this->id = $id;
		}
		if($where) $this->where = $where;
		if($list) $this->data_listing = $this->getAll($admin, $admin);
		
	}
	
	public function formatData($input_array){
		if(empty($input_array)) return array();
		foreach($input_array as $c){
			$array[$c['name']] = $c['id'];
		}
		
		ksort($array);
		$array = array_flip($array);
		return $array;
	}
	
	private function setWhere($where){
		$this->where = $where;
	}
	
	private function setAdmin($admin = true){
		$this->admin = $admin;
	}
	
	
	public static function getTime($type, $time = '', $return_format = 'PHP'){
		if($time == '') $time = time();

		if($type == 'week_from_time'){
			$new_time = $time + SECONDS_IN_WEEK;
		} elseif($type == 'start_this_week') {
			$day_of_week = date('N', $time);
			$day = date('j', $time);
			$start_day_of_week = $day - ($day_of_week - 1);
			$new_time = mktime(0, 0, 1, date('n', $time), $start_day_of_week, date('Y', $time));
		} elseif($type == 'end_this_week') {
			$day_of_week = date('N', $time);
			$day = date('j', $time);
			$end_day_of_week = $day + (7 - $day_of_week);
			$new_time = mktime(24,59,59,date('n', $time), $end_day_of_week, date('Y', $time));
		} elseif($type == 'plus_three_weeks'){
			$new_time = $time + (SECONDS_IN_WEEK * 3);
		} elseif($type == 'end_this_month'){
			// todo
		} elseif($type == 'start_this_month'){
			// todo
		} elseif($type == 'start_day'){
			$new_time = mktime(0,0,0,date('n', $time), date('j', $time), date('Y', $time));
		}

		if($return_format == 'PHP') return $new_time;
		else return date('Y-m-d H:i:s', $new_time);
		
	}

	public static function nl2pimport($text){

		$text_array = explode("\n", $text);
		$text_array = array_map("trim", $text_array);
		
		$text = implode("</p>\n\n<p>", $text_array);
		$text = str_replace("<p></p>\n\n", '', $text);
		$text = '<p>'.$text.'</p>';
		
		return $text;

    }

	public static function nl2p ($text){

		$text = str_replace('\r','',$text);
        $text = '<p>' . $text . '</p>';
        $text = str_replace('\n','</p><p>',$text);
		$text = str_replace('<p></p>','',$text);

        $text = str_replace('<p><img', '<img', $text);
        $text = str_replace('</span></p>', '</span>', $text);
        $text = str_replace('<p><div', '<div', $text);
        $text = str_replace('<p><span class="caption"', '<span class="caption"', $text);
        $text = str_replace('<p><div align="center"></p>', '<div align="center">', $text);
        
        $text = str_replace('</p><p>', "</p>\n\n<p>", $text);
        $text = str_replace('</p><div ', "</p>\n\n<div ", $text);
        $text = str_replace("align=\"center\">\n\n<img", "align=\"center\">\n<img", $text);
        $text = str_replace("<br >\n\n<span class=", "<br >\n<span class=", $text);
        $text = str_replace('<div align="center"><img src="/images/screenshots', "<div align=\"center\">\n<img src=\"/images/screenshots", $text);
        
		return $text;
    }


	public static function getTimeString($start, $end = 0){
		if($end == '0000-00-00 00:00:00') $end = 0;

		$string = '';

		$start_mod = strtotime($start);
		$end_mod = strtotime($end);

		if(date('Ymd',$start_mod)==date('Ymd',$end_mod)){
			return date('l, jS \of F', $start_mod);
		} else {
			$start_string = date('l, jS \of F g:ia', $start_mod);
			$end_string = ' to '. date('l, jS \of F g:ia', $end_mod);
			$string = $start_string.$end_string;
		}

		return $string;

	}

	public static function getMonthsArray(){
		$array = array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		unset($array[0]);
		return $array;
	}
	
	public static function drawDiv($id = false, $clear = false){
		if($id) return '<div id="'.$id.'">';
		elseif($clear) return '<div style="clear: both;"></div>';
		else return '</div>';
	}
	
	public static function drawFieldset($id = false, $label = false){
		if($id){
			$str = '<fieldset id="'.$id.'">';
			if($label){
				$str .= "\n<legend>".$label."</legend>";
			}
			return $str;
		} else return '</fieldset>';
	}
	
	public static function getAttrString($array){
		if(empty($array)) return false;
		$str = '';
		foreach($array as $k => $v){
			$str .= $k.'="'.$v.'" ';
		}
		return $str;
	}
	
	public static function drawSelect($name, $array, $preset = false, $default = false, $label = false, $attr = false){
		$string = '<select name="'.$name.'" '.Site::getAttrString($attr).' id="'.$name."\">\n";
		foreach($array as $key => $val){
			$string .= '<option value="'.$key.'"'.( $preset == $key || !$preset && $default == $key ? ' selected' : '' ).'>'.$val."</option>\n";
		}
		$string .= "</select>\n";
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	
	
	public static function drawRadio($name, $array, $preset = false, $default = false, $label = false){
		$string = '';
		$i = 1;
		foreach($array as $key => $val){
			$string .= '<input type="radio" name="'.$name.'" id="'.$name.'_'.$i.'" value="'.$key.'"'.( $preset == $key || !$preset && $default == $key ? ' checked' : '' ).'>'.$val;
			//if($break) $string .= "<br>\n";
			$i++;
		}
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	public static function drawHidden($name, $value){
		return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
	}
	
	public static function drawSubmit($name, $label){
		return '<input type="submit" name="'.$name.'" id="'.$name.'" value="'.$label.'">';
	}
	
	public static function drawLabel($name, $label){
		return '<label for="'.$name.'" id="'.$name.'_label">'.($label === true ? ucwords(str_replace('_',' ', $name)) : $label).'</label>';
	}
	
	public static function drawText($name, $value = false, $label = false, $attr = false){
		$string = '<input type="text" name="'.$name.'" id="'.$name.'" value="'.$value.'"  '.Site::getAttrString($attr).'>';
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	public static function drawPassword($name, $value = false, $label = false, $attr = false){
		$string = '<input type="password" name="'.$name.'" id="'.$name.'" value="'.$value.'"  '.Site::getAttrString($attr).'>';
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	public static function drawEmail($name, $value = false, $label = false, $attr = false){
		$string = '<input type="email" name="'.$name.'" id="'.$name.'" value="'.$value.'"  '.Site::getAttrString($attr).'>';
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	public static function drawTextArea($name, $value = false, $label = false){
		$string = '<textarea name="'.$name.'" id="'.$name.'">'.$value.'</textarea>';
		
		if($label) return Site::drawLabel($name, $label) . $string;
		else return $string;
	}
	
	public static function drawCheckbox($name, $value = false, $checked, $label = false, $add_hidden = true){
		$string = '';
		if($add_hidden) $string .= Site::drawHidden($name, 0);
		
		$string .= '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
		
		if($label) $string = Site::drawLabel($name, $label) . $string;
		
		return $string;
	}
	
	public static function drawForm($name='', $action = '', $method = 'POST', $enc = '', $block_submit = false, $attr = false){
		if($name!='') return '<form action="'.$action.'" name="'.$name.'" '.Site::getAttrString($attr).' id="'.$name.'" method="'.$method.'"'.($block_submit?' onSubmit="return false"':'').'>';
		else return '</form>';
	}
	
	public static function getLookupTable($table, $id, $value, $order = false, $active = false){
		$query = 'SELECT * FROM '.$table.' WHERE 1 ';
		if($active) $query .= ' AND active = 1';
		if($order) $query .= ' ORDER BY '.$order;
		
		return Site::getData($query, false, $id, $value);
	}
	
	public function getData($query, $single = false, $key_by_id = false, $value = false, $group = false){
		
		$result = $GLOBALS['db']->query($query);
		
		if(!$result) $GLOBALS['debug']->query_fail($query, true);
		
		$array = array();
		
		while($row = $result->fetch_assoc()){
			$row = array_map('stripslashes', $row);
			if($single) return $row;
			
			if(!$key_by_id) $array[] = $row;
			else{
				if(!$value && !$group) $array[$row[$key_by_id]] = $row;
				else if($group) $array[$row[$group]][$row[$key_by_id]] = $row;
				else $array[$row[$key_by_id]] = $row[$value];
			}
		}
		
		
		return $array;
	}
	
	public function getGetVars(){
		
		
		if(isset($_GET['ob'])){
			$array['order_by'] = $_GET['ob'];
		} else if ($this->admin){
			$array['order_by'] = $this->table_fields[0];
		} else if(in_array('order_num', $this->table_fields)){
			$array['order_by'] = (isset($this->table_alias)?$this->table_alias.'.':'').'order_num';
		} else if(in_array('publish_date', $this->table_fields)){
			$array['order_by'] = 'publish_date';
		} else {
			$array['order_by'] = $this->table_fields[0];
		}
		
		
		$array['order_direction'] = isset($_GET['od']) ? $_GET['od'] : ($array['order_by'] == 'order_num'?'ASC':'DESC');
		$array['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		$array['records_displayed'] = isset($_GET['num']) ? $_GET['num'] : 50;
		$array['offset'] = $array['page'] * $array['records_displayed'] - $array['records_displayed'];
		
		return $array;
	}
	
	public function getAll($get_inactive = false, $get_unpublished = false, $key_by_id = false){
		
		$get_vars = $this->getGetVars();
		
		if(isset($this->field_list)) $field = str_replace('KEY_AES', KEY_AES, $this->field_list);
		else $field = '*';
		
		$query = 'SELECT SQL_CALC_FOUND_ROWS '.$field.' FROM '.$this->table_name;
		if(isset($this->table_alias))$query .= ' AS '.$this->table_alias;
		if(isset($this->table_field_mapping)){
			$sorted_mapping = $this->groupTableFieldMapping();
			foreach($sorted_mapping['select'] as $key => $value){
				$query .= ' LEFT JOIN ' . $value['table'] . ' AS ' . $value['as'] . ' ON ' . $value['on'];
			}
		}
		$query .= ' WHERE 1';
		if(!$get_unpublished && in_array('publish_date', $this->table_fields)) $query .= ' AND publish_date < NOW() ';
		//if(!$get_inactive) $query .= ' AND '.(isset($this->table_alias)?$this->table_alias.'.':'').'active = 1';
		if($this->where) $query .= ' AND '.$this->where;
		$query .= ' ORDER BY '.$get_vars['order_by'].' '.$get_vars['order_direction']; 
		$query .= ' LIMIT ' . $get_vars['offset'] . ',' . $get_vars['records_displayed'];
		
		return $this->getData($query, false, $key_by_id);
	}
	
	private function checkCache($class, $id = 'id'){
		$file_location = $_SERVER['DOCUMENT_ROOT'].'/classes/cache/'.strtolower($class).'.php';
		
		if(file_exists($file_location)){
			$array = unserialize(file_get_contents($file_location));
		} else {
			$array = $this->getAll(false, false, $id);
			file_put_contents($file_location, serialize($array));
		}
		return $array;
	}
	
	private function removeCache($class){
		unlink($_SERVER['DOCUMENT_ROOT'].'/classes/cache/'.strtolower($class).'.php');
	}
	
	private function getMethodCache($class, $method, $return = false){
		$file_location = $_SERVER['DOCUMENT_ROOT'].'/classes/cache/'.strtolower($class).'_'.strtolower($method).'.php';
		if(!file_exists($file_location)){
			return false;
		} else {
			if($return){
				return unserialize(file_get_contents($file_location));
			} else {
				return true;
			}
		}
	}
	
	
	function urlIn($str){
		if($str=='') return false;
		
		$encrypt = new Encrypt(KEY_URL);
		
		return $encrypt->decrypt(base64_decode(str_replace('_','/',$str)));
	}
	
	function urlOut($str){
		if($str=='') return false;
		
		$encrypt = new Encrypt(KEY_URL);
		
		return str_replace('/','_',base64_encode($encrypt->encrypt($str)));
	}
	
	private function setMethodCache($class, $method, $data){
		$file_location = $_SERVER['DOCUMENT_ROOT'].'/classes/cache/'.strtolower($class).'_'.strtolower($method).'.php';
		file_put_contents($file_location, serialize($data));
	}
	
	private function groupTableFieldMapping(){
		$array = $this->table_field_mapping;
		foreach($array as $key => $value){
			$new_array[$value['type']][$key] = $value;
		}
		return $new_array;
	}
	
	public function getOne($id){
		if(isset($this->field_list)) $field = str_replace('KEY_AES', KEY_AES, $this->field_list);
		else $field = '*';
		
	    $alias = isset($this->table_alias) ?' AS '.$this->table_alias:'';
	    $query = 'SELECT '.$field.' FROM '.$this->table_name . $alias .' WHERE ' . $this->table_fields[0] . ' = ' . $id;
	    $data = $this->getData($query, true);
		return array_map('stripslashes', $data);
    }
	
	public function runQuery($query, $conn = false){
		$db = $conn ? $conn : $GLOBALS['db'];
		
		$result = $db->query($query);
		
		if(!isset($this)) return true;
		//$result = mysql_query($query);
		if(!$result){
			$GLOBALS['debug']->query_fail($query, true);
		} else {
			if($db->insert_id == ''){
				return $_POST[$this->table_fields[0]];
			} else {
				return $db->insert_id;
			}
		}
	}
	
	public function save(){
		$field_string = '';
		$value_string = '';
		$set_string = '';
		$i=0;
		
		$is_new = $_POST[$this->table_fields[0]] == 0 ? true : false;
		
		foreach($this->table_fields as $field){
			if(!isset($_POST[$field])) continue;
			$field_string .= ($i?',':'') . $field;
			
			$_POST[$field] = $GLOBALS['db']->real_escape_string(stripslashes($_POST[$field]));
			
			if(isset($this->crypt_array) && in_array($field, $this->crypt_array)){
				$set_string .= ($i?', ':'') . $field . ' = AES_ENCRYPT("' . $_POST[$field] . '","'.KEY_AES.'")';
				$value_string .= ($i?',':'') . 'AES_ENCRYPT("' . $_POST[$field] . '","'.KEY_AES.'")';
			} else {
				
				$set_string .= ($i?', ':'') . $field . ' = "' . $_POST[$field] . '"';
				$value_string .= ($i?',':'') . '"' . $_POST[$field] . '"';
				
				
				
				/*
				if($field == 'unique_id'){
					$set_string .= ($i?', ':'') . ' unique_id = "' . $_POST[$field] . '"';
					$value_string .= ($i?',':'') . '"' . $_POST[$field] . '"';
				} else {
					if(){
					
					} else {
					
					}
				}
				*/
				
			
			}
			
			$i++;
		}
		
		if(isset($this->uid) ){
			$field_string .= ', unique_id';
			$value_string .= ', UUID()';
		}
		
		
		if($is_new){
			$query = 'INSERT INTO '.$this->table_name.'('.$field_string.') VALUES('.$value_string.')';
		} else {
			$query = 'UPDATE '.$this->table_name.' SET '.$set_string . ' WHERE '.$this->table_fields[0].' = ' . $_POST[$this->table_fields[0]] . (isset($_POST['unique_id'])?' AND unique_id = "'.$_POST['unique_id'].'"':'');
		}
		
		
		$id = $this->runQuery($query);
		
		if(isset($_GET['debug_login'])){
			echo '<br>Query';
			$GLOBALS['debug']->printr($query);
			
			echo '<br>Post';
			$GLOBALS['debug']->printr($_POST);
		}
		
		return $id;
	}
	
	public function autop($pee, $br = true) {
		
		if ( trim($pee) === '' ) return '';
		
		$pee = $pee . "\n"; // just to make things a little easier, pad the end
		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		
		// Space things out a little
		$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
		$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
		$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
		$pee = str_replace(array('\r\n', '\r'), "\n", $pee); // cross-platform newlines
		
		if ( strpos($pee, '<object') !== false ) {
			$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
			$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
		}
		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		// make paragraphs, including one at the end
		$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
		
		$pee = '';
		foreach ( $pees as $tinkle )
			$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
		$pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
		$pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
		if ($br) {
			$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $pee);
			$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
			$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
		}
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
		if (strpos($pee, '<pre') !== false)
			$pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'clean_pre', $pee );
		$pee = preg_replace( "|\n</p>$|", '</p>', $pee );
		
		$pee = preg_replace( "|</p>\n<p>|", "</p>\n\n<p>", $pee );
		
		return $pee;
	}
	
}

?>
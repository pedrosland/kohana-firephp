<?php

class Database_MySQL extends Kohana_Database_MySQL{
	
	private $database = 'default';
	
	public function query($type, $sql, $as_object){
		if(isset($this->_config['connection']) === true){
			$this->database = $this->_config['connection']['database'];
		}
		
		$result = parent::query($type, $sql, false);
		
		if($type === Database::SELECT){
			$table = array();
			if(count($result) > 0){
				foreach($result->current() as $key=>$data){
					$table[0][] = $key;
				}
				$result->rewind();
				
				foreach($result as $row){
					$table[] = $row;
				}
				
				$result->rewind();
			}else{
				$table[] = array('No', 'rows');
			}
			
			$group = Profiler::groups();
			$group = Profiler::total($group['database (default)'][$sql][0]);
			
			FirePHP::getInstance()->table($this->database.' : ('.number_format($group[0], 6).'s) '.$sql, $table);
		}elseif($type === Database::INSERT){
			FirePHP::getInstance()->info($this->database.' : Insert id: '.$result[0].' Affected rows: '.$result[1]);
		}else{
			FirePHP::getInstance()->info($this->database.' : Affected rows: '.$result[0]);
		}
		
		return $result;
	}
}
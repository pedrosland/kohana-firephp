<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File log writer.
 *
 * @package    Kohana
 * @author     Pedro Sland
 * @copyright  (c) 2008-2009 Pedro Sland
 * @license    http://kohanaphp.com/license.html
 */
class Log_FirePHP extends Kohana_Log_Writer {

	// Instance of FirePHP
	protected $fire;

	/**
	 * Creates a new file logger.
	 *
	 * @param   string  firePHP options
	 * 							currently does nothing
	 * @return  void
	 */
	public function __construct($options = array())
	{
		$this->fire = FirePHP::getInstance(true);
	}

	/**
	 * Writes each of the messages to the console.
	 *
	 * @param   array   messages
	 * @return  void
	 */
	public function write(array $messages)
	{
		foreach ($messages as $message)
		{
			// Write each message to firePHP
			if($message['type'] === 'ERROR'){
				$this->fire->error($message['body']);
			}else{
				$this->fire->log($message['body']);
			}
		}
	}
	
	// Writes final info to the console
	public function __destruct(){
		$endTime = microtime(true) - START_P_TIME;
		$endMem = (memory_get_usage() - START_P_MEM)/1024;
		
		$group = Kohana_Profiler::groups();
		
		$table = array();
		$table[] = array('Type', 'Time (s)', 'Mem (kb)');
		
		foreach($group as $rName=>$route){
			$table[] = array($rName);
			foreach($route as $tName=>$type){
				foreach($type as $stat){
					$stats = Kohana_Profiler::total($stat);
					$table[] = array(
						$tName,
						number_format($stats[0], 6),
						number_format($stats[1]/1024, 4)
					);
				}
			}
		}
		
		$this->fire->table('Execution stats ('.number_format($endTime, 6).'s '.number_format($endMem, 4).'kb)', $table);
	}
	
} // End Log_FirePHP
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
		$app = Profiler::application();
		
		$group = Profiler::groups();
		
		$table = array();
		$table[] = array('Type', 'Time (s)', 'Mem (kb)');
		
		foreach($group as $rName=>$route){
			$table[] = array($rName);
			foreach($route as $tName=>$type){
				foreach($type as $stat){
					$stats = Profiler::total($stat);
					$table[] = array(
						$tName,
						number_format($stats[0], 6),
						number_format($stats[1]/1024, 4)
					);
				}
			}
		}
		
		$this->fire->info(Session::instance()->as_array(), 'Session');
		
		$this->fire->group('Stats: '.$app['count']);
		$this->fire->info('Min:	'.number_format($app['min']['time'], 6).'s '.number_format($app['min']['memory']/1024, 4).'kb');
		$this->fire->info('Max:	'.number_format($app['max']['time'], 6).'s '.number_format($app['max']['memory']/1024, 4).'kb');
		$this->fire->info('Average: '.number_format($app['average']['time'], 6).'s '.number_format($app['average']['memory']/1024, 4).'kb');
		$this->fire->info('Total:	'.number_format($app['total']['time'], 6).'s '.number_format($app['total']['memory']/1024, 4).'kb');
		$this->fire->groupEnd();
		//$this->fire->table('Execution stats ('.number_format($endTime, 6).'s '.number_format($endMem, 4).'kb)', $table);
	}
	
} // End Log_FirePHP
<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\utils;

use pocketmine\Thread;

class ServerKiller extends Thread {

	public $time;

	/** @var bool */
	private $stopped = false;

	/**
	 * ServerKiller constructor.
	 *
	 * @param int $time
	 */
	public function __construct($time = 15){
		$this->time = $time;
	}

	public function run(){
		$this->registerClassLoader();
		$start = time();
		$this->synchronized(function(){
			if(!$this->stopped){
			    $this->wait($this->time * 1000000);
		    }
		});
		if(time() - $start >= $this->time){
			echo "\nTook too long to stop, server was killed forcefully!\n";
			@Utils::kill(getmypid());
		}
	}

	public function quit() : void{
		$this->synchronized(function() : void{
			$this->stopped = true;
			$this->notify();
		});
		parent::quit();
	}

	/**
	 * @return string
	 */
	public function getThreadName(){
		return "Server Killer";
	}
}

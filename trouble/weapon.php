<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble;

class Weapon {
	private static $pdo;
	private static $cache = array();

	public $id;
	private $data;

	public static function attach_pdo(\PDO $pdo) {
		self::$pdo = $pdo;
	}

	public function __construct($id=False) {
		if(!(self::$pdo instanceof \PDO)) {
			throw new Exception("Weapons does not have database attached.");
		}
		if(empty(self::$cache)) {
			self::populate_cache();
		}
		if($id) {
			$this->id = $id;
			$this->load_from_cache();
		}
	}

	private function load_from_cache() {
		$this->data = self::$cache[$this->id];
	}

	/**
	 * Loads all of the weapons into the cache. This should be fine for < 100
	 * or so weapons. Can always be modified if more weapons needed. Fairly schema ignorant.
	 **/
	private static function populate_cache() {
			$sth = self::$pdo->prepare("
				SELECT *
				FROM weapons
			");
			$sth->execute();
			while($item = $sth->fetchObject()) {
				self::$cache[$item->id] = $item;
			}
	}

	public function __get($key) {
		return $this->data->$key;		
	}
	public function __set($key, $value) {
		return $this->data->$key = $value;		
	}
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A db model class for visitor
 * Properly, an abstract model class with ORM should be used.
 *
 * @author timofey
 */
class Visit
{
	/**
	 *
	 * @var PDO - database handler.
	 *
	 */
	protected $db;
	protected $uid; // id of the visit if updating
	protected $table = 'shows';

	/**
	 * Establish a database connection on connect
	 * @param array $dbc
	 */
	public function __construct(Array $dbc)
	{
		$this->db = new \PDO($dbc['dsn'], $dbc['user'], $dbc['pass']);
	}

	/**
	 * Save page hit on banner show
	 * @param String $ip
	 * @param String $userAgent
	 * @param String $page
	 */
	public function save($ip, $userAgent, $page)
	{
		try {
			$this->db->beginTransaction(); // transaction is used to queue parallel updates
			if($this->hasVisited($ip, $userAgent, $page)) {
				$this->incCounter();
			}
			else {
				$this->addVisit($ip, $userAgent, $page);
			}
			$this->db->commit();
		}
		catch (\Exception $x) {
			$this->db->rollBack();
		}
	}

	/**
	 * Returns true if the visitor has already visited the page
	 * @param String $ip - user's IP address
	 * @param String $userAgent - user agent string
	 * @param String $page page url
	 * @return boolean - true on success
	 * @throws \RuntimeException
	 * @see uid - saved on successful query
	 */
	protected function hasVisited($ip, $userAgent, $page)
	{
		$select = "SELECT uid FROM ". $this->table .
				" WHERE ip_address = ? AND user_agent = ? AND page_url = ?";

		$st = $this->db->prepare($select);
		if(!$st->execute([$ip, $userAgent, $page]))
		{
			throw new \RuntimeException('Failed to query visitor data');
		}
		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (null === $row) {
			return false; // no previous visits by this user are recorded
		}
		else {
			$this->uid = $row['uid'];
			return true;
		}
	}

	/**
	 * Increments counter for uid previoisly queried by $this->hasVisited
	 * @throws \RuntimeException
	 */
	protected function incCounter()
	{
		if(!$this->uid) {
			throw new \RuntimeException('Cannot update counter for an uninitialized visit');
		}
		// timestamp is updated by mysql
		$select = "UPDATE ". $this->table . " SET view_count = view_count + 1 WHERE uid = ?";
		$st = $this->db->prepare($select);
		if(!$st->execute([$this->uid])) {
			throw new \RuntimeException('Failed to update page view count');
		}
	}

	/**
	 * Inserts new user data
	 * @param String $ip
	 * @param String $userAgent
	 * @param String $page
	 * @throws \RuntimeException
	 */
	protected function addVisit($ip, $userAgent, $page)
	{
		$query = "INSERT INTO ". $this->table .
				" SET ip_address = ?, user_agent = ?, page_url = ?, views_count = 1";
		$st = $this->db->prepare($query);
		if(!$st->execute([$ip, $userAgent, $page])) {
			throw new \RuntimeException('Failed to save user visit data');
		}
	}
}

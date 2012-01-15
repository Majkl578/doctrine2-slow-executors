<?php

namespace Entities;

/**
 * @Entity
 */
class SubA extends Root
{
	/**
	 * @Column(type="integer")
	 */
	private $foo;

	public function getFoo()
	{
		return $this->foo;
	}

	public function setFoo($foo)
	{
		$this->foo = $foo;
	}
}
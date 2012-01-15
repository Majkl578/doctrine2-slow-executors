<?php

namespace Entities;

/**
 * @Entity
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"root" = "Root", "a" = "SubA"})
 */
class Root
{
	/**
	 * @Column(type="integer")
	 * @Id
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(type="integer")
	 */
	private $xyz;

	public function getId()
	{
		return $this->id;
	}

	public function getXyz()
	{
		return $this->xyz;
	}

	public function setXyz($xyz)
	{
		$this->xyz = $xyz;
	}
}
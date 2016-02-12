<?php

class Membre
{
	private $pseudo;
	private $email;
	private $actif;


	public function __construct($pseudo){
		$this->pseudo = $pseudo;
		$this->actif = true;
	}

	public function getPseudo()
	{
		return $this->pseudo;
	}

	public function setPseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}
}
?>
<?php
	include("include/all.php");
	load_model("singletons");
	load_model("rapporteur");
	load_model("rapporteurPDO");
	
	$mvc->state("index","index","index");
	
	$mvc->state("connectionAction","connectionAction");
		
	$mvc->state("inscriptionAction","inscriptionAction");
		
	$mvc->state("inscription","inscription","inscription");
	
	$mvc->state("inscsrftest","inscsrftest");
		
	$mvc->state("connexionRapporteur","connexionRapporteur");
	
	$mvc->state("changePassword","changePassword","changePassword");
	
	$mvc->state("changePasswordAction","changePasswordAction");
	
	$mvc->dstate("error404","error404");
	
	$mvc->state("disconnect","disconnect");
	
	$mvc->start();

<?php
	include("include/all.php");
	load_model("singletons");
	load_model("rapporteur");
	load_model("rapporteurPDO");
	
	Cookie::setConfigFile("key",4096);
	Cookie::httponly();
	
	$mvc->state("index","index","index");
	
	$mvc->state("root","root","root");
	
	$mvc->state("connectionAction","connectionAction");
		
	$mvc->state("inscriptionAction","inscriptionAction");
		
	$mvc->state("inscription","inscription","inscription");
		
	$mvc->state("connexionRapporteur","connexionRapporteur");
	
	$mvc->state("changePassword","changePassword","changePassword");
	
	$mvc->state("changePasswordAction","changePasswordAction");
	
	$mvc->state("deleteRapporteur","deleteRapporteur");
	
	$mvc->state("disconnect","disconnect");
	
	$mvc->state("addDossier","addDossier","addDossier");
	
	$mvc->state("addDossierAction","addDossierAction");
	
	$mvc->state("admin","admin","admin");
	
	$mvc->state("adminAction","adminAction");
	
	$mvc->dstate("error404","error404");
	
	$mvc->start();

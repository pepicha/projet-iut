<?php
	if(RapporteurPDO::isRoot()){
		$form=new Form("inscription");
		if($form->isCommitted()){
			if($_POST["password1"]==$_POST["password2"]){
				$rapporteur=new Rapporteur(Data::$post->get("nom"),Data::$post->get("prenom"),Data::$post->get("login"),Data::$post->get("password1"),array("day"=>Data::$post->get("dnaiss"),"month"=>Data::$post->get("mnaiss"),"year"=>Data::$post->get("ynaiss")),Data::$post->get("mail"));
				if(RapporteurPDO::addRapporteur($rapporteur)){
					echo Data::$post->get("password1");
				}
			}
		}
	}

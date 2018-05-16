<?php
/*************************************
 * @project: 	Xenomorph
 * @file:		CLASS XLdap
 * @author: 	Mickaël POLLET
 *************************************/

class XLdap extends XClass
{

/******************************************************/
/*****************     PARAMETRES     *****************/
/******************************************************/

	//use XSystem;

	// Propriétés par défaut

  // Déclaration des propriétés
  public function setClassProperties() {
		$this->property('cn',							'string');    // URL du service OneSignal à contacter
    $this->property('sn',							'string');    // Clé API de l'application OneSignal
    $this->property('uid',						'string');    // ID de l'application OneSignal à contacter
    $this->property('dn',							'string');     // Segments OneSignal d'utilisateurs à contacter. Doivent être paramétrés sur OneSignal
    $this->property('displayName',		'string');     // Filtres spécifiques à contacter
    $this->property('givenName',			'string');    // Titre de la notification
    $this->property('postalCode',			'string');    // Sous-titre de la notification
    $this->property('l',							'string');    // Message de la notification
    $this->property('title',					'string');     // Message de la notification
		$this->property('employeeNumber',	'string');     // Message de la notification
		$this->property('mail',           'string');     // Message de la notification
		$this->property('objectclass',		'string');     // Message de la notification
  }

/**********************************************************/
/*****************     FIN PARAMETRES     *****************/
/**********************************************************/

/********************************************************/
/*****************     CONSTRUCTEUR     *****************/
/********************************************************/

	public function __construct(array $ldapUser = array()) {			// Constructeur dirigé vers la méthode d'hydratation
		parent::__construct($ldapUser);
	}

/************************************************************/
/*****************     FIN CONSTRUCTEUR     *****************/
/************************************************************/

/*******************************************************/
/*****************     HYDRATATION     *****************/
/*******************************************************/
/***********************************************************/
/*****************     FIN HYDRATATION     *****************/
/***********************************************************/

/***************************************************/
/*****************     GETTERS     *****************/
/***************************************************/
/*******************************************************/
/*****************     FIN GETTERS     *****************/
/*******************************************************/

/***************************************************/
/*****************     SETTERS     *****************/
/***************************************************/
/*******************************************************/
/*****************     FIN SETTERS     *****************/
/*******************************************************/
}
?>

<?php
/*************************************
 * @project: 	Xenomorph
 * @file:		CLASS XLdapConnection
 * @author: 	Mickaël POLLET
 *************************************/

class XLdapConnection extends XClass
{

/******************************************************/
/*****************     PARAMETRES     *****************/
/******************************************************/

	//use XSystem;

	// Propriétés par défaut

	// Déclaration des propriétés
  public function setClassProperties() {
		$this->property('server',					'string');    // URL du service OneSignal à contacter
    $this->property('port',						'string');    // Clé API de l'application OneSignal
		$this->property('user',						'string');    // Clé API de l'application OneSignal
		$this->property('password',				'string');    // Clé API de l'application OneSignal
    $this->property('base',						'string');    // Segments OneSignal d'utilisateurs à contacter. Doivent être paramétrés sur OneSignal
  }

/**********************************************************/
/*****************     FIN PARAMETRES     *****************/
/**********************************************************/

/********************************************************/
/*****************     CONSTRUCTEUR     *****************/
/********************************************************/

	public function __construct(array $ldapConnection = array()) {			// Constructeur dirigé vers la méthode d'hydratation
		parent::__construct($ldapConnection);
		$this->setPort();
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

	public function setPort($port = null) {
		if ($port === null) {
			parent::setPort("389");
		} else {
			parent::setPort($port);
		}
	}

/*******************************************************/
/*****************     FIN SETTERS     *****************/
/*******************************************************/
}
?>

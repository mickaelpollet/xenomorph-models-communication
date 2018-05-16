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
		$this->property('server',					'string');    // Serveur LDAP
    $this->property('port',						'string');    // Port de communication
		$this->property('login',					'string');    // Login de connexion
		$this->property('password',				'string');    // Mot de passe de connexion
    $this->property('base',						'string');    // Base de recherche LDAP
    $this->property('attributes',			'array');     // Tableau contenant les atttributs LDAP à utiliser
    $this->property('search',			    'string');    // Modèle de recherche
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

<?php
/*************************************
 * @project: 	Xenomorph - Models - XCurl
 * @file:		MODEL CLASS XCurl
 * @author: 	Mickaël POLLET
 *************************************/

class XCurl extends XClass
{

  /******************************************************/
  /*****************     PROPRIETES     *****************/
  /******************************************************/

  // Propriétés par défaut
  private $_type                = 1;
  private $_connection_timeout  = 10;
  private $_request_timeout     = 20;
  private $_verbose             = true;

  // Déclaration des propriétés
  public function setClassProperties() {
		$this->property('url',                  'string');      // URL à contacter
    $this->property('type',                 'integer');     // 1 - POST, 2 - GET
    $this->property('authentication',       'string');     // Paramètre d'authentification
    $this->property('proxy',                'boolean');     // Paramètre d'authentification à un serveur Proxy
    $this->property('proxy_protocol',       'string');      // Protocole utilisé pour se connecter au serveur Proxy
    $this->property('proxy_server',         'string');      // Adresse du serveur Proxy
    $this->property('proxy_port',           'string');      // Port de connexion au serveur Proxy
    $this->property('proxy_login',          'string');      // Login utilisé pour se connecter au serveur Proxy
    $this->property('proxy_pwd',            'string');      // Mot de passe utilisé pour se connecter au serveur Proxy
    $this->property('connection_timeout',   'integer');     // Timeout de la requête
    $this->property('request_timeout',      'integer');     // Timeout de la requête
    $this->property('header',               'array');       // Timeout de la requête
    $this->property('request',              'string');      // Elements à envoyer
    $this->property('verbose',              'boolean');     // Verbosité de la requête
	}

  /******************************************************/
  /***************     FIN PROPRIETES     ***************/
  /******************************************************/

  /********************************************************/
  /*****************     CONSTRUCTEUR     *****************/
  /********************************************************/
  public function __construct($class_datas = array()) {				// Constructeur dirigé vers la méthode d'hydratation

    parent::__construct();

    if ($this->type() == null) {
      $this->setType($this->_type);
    }

    if ($this->connection_timeout() == null) {
      $this->setConnection_timeout($this->_connection_timeout);
    }

    if ($this->request_timeout() == null) {
      $this->setRequest_timeout($this->_request_timeout);
    }

    if ($this->verbose() == null) {
      $this->setVerbose($this->_verbose);
    }

  }
  /********************************************************/
  /**************     FIN CONSTRUCTEUR     ****************/
  /********************************************************/

  /***************************************************/
  /*****************     SETTERS     *****************/
  /***************************************************/
  /*******************************************************/
  /*****************     FIN SETTERS     *****************/
  /*******************************************************/

}

?>

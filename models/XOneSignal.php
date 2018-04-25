<?php
/*************************************
 * @project: 	Xenomorph - Models - XOneSignal
 * @file:		MODEL CLASS XOneSignal
 * @author: 	Mickaël POLLET
 *************************************/

class XOneSignal extends XClass
{

  /******************************************************/
  /*****************     PROPRIETES     *****************/
  /******************************************************/

  // Propriétés par défaut
  private $_service_url = "https://onesignal.com/api/v1/notifications";
  private $_segments    = array('Active Users');
  private $_filters     = array();
  private $_title       = array();
  private $_subtitle    = array();

  // Déclaration des propriétés
  public function setClassProperties() {
		$this->property('service_url',          'string');    // URL du service OneSignal à contacter
    $this->property('api_key',              'string');    // Clé API de l'application OneSignal
    $this->property('app_id',               'string');    // ID de l'application OneSignal à contacter
    $this->property('segments',             'array');     // Segments OneSignal d'utilisateurs à contacter. Doivent être paramétrés sur OneSignal
    $this->property('filters',              'array');     // Filtres spécifiques à contacter
    $this->property('title',                'array');    // Titre de la notification
    $this->property('subtitle',             'array');    // Sous-titre de la notification
    $this->property('message',              'array');    // Message de la notification
    $this->property('badge',                'string');     // Message de la notification
  }

  /******************************************************/
  /***************     FIN PROPRIETES     ***************/
  /******************************************************/

  /********************************************************/
  /*****************     CONSTRUCTEUR     *****************/
  /********************************************************/
  public function __construct($class_datas = array()) {				// Constructeur dirigé vers la méthode d'hydratation

    parent::__construct();

    // Affectation de l'URL par défaut pour le service OneSignal
    if ($this->service_url() == null) {
      $this->setService_url($this->_service_url);
    }

    // Affectation des segments par défaut
    if (empty($this->segments())) {
      $this->setSegments($this->_segments);
    }

    // Affectation des filtres par défaut
    if (empty($this->filters())) {
      $this->setFilters($this->_filters);
    }

    // Affectation des filtres par défaut
    if (empty($this->title())) {
      $this->setTitle($this->_title);
    }

    // Affectation des filtres par défaut
    if (empty($this->subtitle())) {
      $this->setSubtitle($this->_subtitle);
    }

  }
  /********************************************************/
  /**************     FIN CONSTRUCTEUR     ****************/
  /********************************************************/

  /***************************************************/
  /*****************     SETTERS     *****************/
  /***************************************************/

    // Affectation d'une valeur en fonction de la langue
    private function addLanguageMessage($messages_array, $message, $lang) {
      $current_messages = $messages_array;

      if ($lang === null) {
        $current_messages["en"] = $message;
      } else {
        if (empty($current_messages["en"])) {
          $current_messages["en"] = $message;
          $current_messages[$lang] = $message;
        } else {
          $current_messages[$lang] = $message;
        }
      }

      return $current_messages;
    }

    // Méthode d'ajout d'un filtre individuel
    public function addFilter($filter) {
      $current_filters = $this->filters();                  // Récupération des filtres déjà présents
      $current_filters[] = $filter;                         // Ajout du nouveau filtre
      $this->setFilters($current_filters);                  // Mise à jour de l'objet
    }

    // Méthode d'ajout d'un filtre individuel
    public function addTitle($message, $lang = null) {
      $current_messages = $this->title();
      $new_messages = self::addLanguageMessage($current_messages, $message, $lang);
      $this->setTitle($new_messages);
    }

    // Méthode d'ajout d'un filtre individuel
    public function addSubtitle($message, $lang = null) {
      $current_messages = $this->subtitle();
      $new_messages = self::addLanguageMessage($current_messages, $message, $lang);
      $this->setSubtitle($new_messages);
    }

    // Méthode d'ajout d'un message
    public function addMessage($message, $lang = null) {
      $current_messages = $this->message();
      $new_messages = self::addLanguageMessage($current_messages, $message, $lang);
      $this->setMessage($new_messages);
    }

  /*******************************************************/
  /*****************     FIN SETTERS     *****************/
  /*******************************************************/

}

?>

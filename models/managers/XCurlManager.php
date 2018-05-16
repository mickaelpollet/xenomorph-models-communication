<?php
/*************************************
 * @project: 	Xenomorph - Models - XCurlManager
 * @file:		XCurl CLASS MANAGER
 * @author: 	Mickaël POLLET
 *************************************/

class XCurlManager
{

  /******************************************************/
  /*****************     PROPERTIES     *****************/
  /******************************************************/
  /******************************************************/
  /***************     END PROPERTIES     ***************/
  /******************************************************/

  /********************************************************/
  /*****************     CONSTRUCTOR     ******************/
  /********************************************************/
  /********************************************************/
  /**************     END CONSTRUCTOR     *****************/
  /********************************************************/

  /***************************************************/
  /*****************     GETTERS     *****************/
  /***************************************************/
  /*******************************************************/
  /*****************     END GETTERS     *****************/
  /*******************************************************/

  /***************************************************/
  /*****************     SETTERS     *****************/
  /***************************************************/
  /*******************************************************/
  /*****************     END SETTERS     *****************/
  /*******************************************************/

  /************************************************************/
  /*********************     METHODS     **********************/
  /************************************************************/

  public static function connect($XCurlObject)	{

    // Initialisation de la connexion CURL
    $CurlConnection = curl_init();

    // Affectation des paramètres d'authentification
    if ($XCurlObject->authentication() != null) {
      $authentication_string = "Authorization:Basic ".$XCurlObject->authentication();
      $current_header = $XCurlObject->header();
      $current_header[] = $authentication_string;
      $XCurlObject->setHeader($current_header);
    }

    // Mise en place des identifiants
    $proxy_ids = "";
    if ($XCurlObject->proxy_login() != "" && $XCurlObject->proxy_pwd() != "") {
      $proxy_ids = $XCurlObject->proxy_login().":".$XCurlObject->proxy_pwd()."@";
    }

    // Paramétrage du proxy
    if ($XCurlObject->proxy() === true) {
      curl_setopt($CurlConnection,   CURLOPT_PROXY,         $XCurlObject->proxy_protocol()."://".$proxy_ids.$XCurlObject->proxy_server().":".$XCurlObject->proxy_port());
    }

    // Mise en place de la connexion en méthode POST
    if ($XCurlObject->type() == 1) {
      curl_setopt($CurlConnection,  CURLOPT_POST,           true);
      curl_setopt($CurlConnection,  CURLOPT_POSTFIELDS,     $XCurlObject->request());
    } else {
    //  curl_setopt($CurlConnection,  CURLOPT_POST,           false);
    }

    // Affectation des paramètres
    curl_setopt($CurlConnection,  CURLOPT_URL,              $XCurlObject->url());
    curl_setopt($CurlConnection,  CURLOPT_RETURNTRANSFER,   true);
    curl_setopt($CurlConnection,  CURLOPT_HTTPHEADER,       $XCurlObject->header());
    curl_setopt($CurlConnection,  CURLOPT_CONNECTTIMEOUT,   $XCurlObject->connection_timeout());
    curl_setopt($CurlConnection,  CURLOPT_TIMEOUT,          $XCurlObject->request_timeout());
    curl_setopt($CurlConnection,  CURLOPT_VERBOSE,          $XCurlObject->verbose());
    curl_setopt($CurlConnection,  CURLOPT_HTTP_VERSION,     CURL_HTTP_VERSION_1_1);

    // Exécution de la connexion
    $CurlConnectionResult = curl_exec($CurlConnection);

    // Récupération des informations de la connexion
    $CurlConnectionInformations = curl_getinfo($CurlConnection);

    // Récupération des erreurs liées à la connexion
    $CurlConnectionErrors = curl_error($CurlConnection);

    // Fermeture de la connexion
    curl_close($CurlConnection);

    return $CurlConnectionResult;

	}

  /************************************************************/
  /******************     END METHODS     *********************/
  /************************************************************/

}

?>

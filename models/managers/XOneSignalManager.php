<?php
/*************************************
 * @project: 	Xenomorph - Models - XOneSignalManager
 * @file:		MANAGER de la calsse XCurl
 * @author: 	Mickaël POLLET
 *************************************/

class XOneSignalManager
{

  /******************************************************/
  /*****************     PARAMETRES     *****************/
  /******************************************************/
  /**********************************************************/
  /*****************     FIN PARAMETRES     *****************/
  /**********************************************************/


  /********************************************************/
  /*****************     CONSTRUCTEUR     *****************/
  /********************************************************/
  /************************************************************/
  /*****************     FIN CONSTRUCTEUR     *****************/
  /************************************************************/


  /***************************************************/
  /*****************     SETTERS     *****************/
  /***************************************************/
  /*******************************************************/
  /*****************     FIN SETTERS     *****************/
  /*******************************************************/


  /************************************************************/
  /*********************     METHODES     ********************/
  /************************************************************/

  public static function sendMessage($XOneSignal) {

    // Préparation de la requête
		$notification_parameters = array(
			'app_id' => $XOneSignal->app_id(),                     // ID de l'application OneSignal à contacter
			'included_segments' => $XOneSignal->segments(),        // Ciblage des segments d'utilisateurs spécifiques
			//'data' => array("foo" => "bar"),
      'filters' => $XOneSignal->filters(),                   // Filtres basés sur le champ TAG, sous la forme : array("field" => "tag",	"key" => "login", "relation" => "=", "value" => "pollet.m@mipih.fr")
      'headings' => $XOneSignal->title(),
      'subtitle' => $XOneSignal->subtitle(),
      'contents' => $XOneSignal->message(),
		);

    // Préparation des Badges
    if ($XOneSignal->badge() != null) {
      preg_match("/^(\+|\-|=)([\d]*)$/", $XOneSignal->badge(), $result);
      if (count($result) == 3) {
        switch ($result[1]) {
          case '+': $notification_parameters['ios_badgeType'] = 'Increase'; $notification_parameters['ios_badgeCount'] = $result[2];  break;
          case '=': $notification_parameters['ios_badgeType'] = 'SetTo';    $notification_parameters['ios_badgeCount'] = $result[2];  break;
          default:  $notification_parameters['ios_badgeType'] = 'None';     $notification_parameters['ios_badgeCount'] = 0;  break;
        }
      }
    }

    $header = array();
    $headers[] = "Content-Type: application/json; charset=utf-8";

    $test = new XCurl();
    $test->setUrl($XOneSignal->service_url());

    // Authentification
    $test->setAuthentication($XOneSignal->api_key());
    $test->setHeader($headers);
    $test->setRequest(json_encode($notification_parameters));
    $XOneSignalRequest = XCurlManager::connect($test);

    return $XOneSignalRequest;

  }

  /************************************************************/
  /*******************     FIN METHODES     *******************/
  /************************************************************/

}

?>

<?php
/****************************************
 * @project: 	Xenomorph
 * @file:		MANAGER de la Class XLdap
 * @author: 	Mickaël POLLET
 *****************************************/

class XLdapManager
{
/******************************************************/
/*****************     PARAMETRES     *****************/
/******************************************************/

	// Instance de connexion LDAP
	private  $_ldapConnexion				= null;
	private  $_ldapConnexionStream	= null;

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


/**********************************************************************/
/*********************     FONCTIONS PUBLIQUES     ********************/
/**********************************************************************/

		/**
	 *
	 * Connexion à l'annuaire LDAP
	 *
	 **/
	public function connexion(XLdapConnection $LdapConnection) {

		try {


			// Connexion au serveur
			$ldap_connexion = ldap_connect($LdapConnection->server(), $LdapConnection->port());

			// Ajout des options de connexion
			ldap_set_option($ldap_connexion, LDAP_OPT_REFERRALS, 0);
			ldap_set_option($ldap_connexion, LDAP_OPT_PROTOCOL_VERSION, 3);

			// Connexion de l'utilisateur
			$ldap_login = ldap_bind($ldap_connexion, $LdapConnection->login(), $LdapConnection->password());

			if ($ldap_login) { // SI la connexion est réussie...

				// on récupère l'instance de connexion


				$this->_ldapConnexion				= $LdapConnection;
				$this->_ldapConnexionStream	= $ldap_connexion;



			/*global $ldapConnexion;
				$ldapConnexion = $ldap_connexion;*/

				// on retourne un message
				return array("result" => "success", "message" => 'Connexion au serveur LDAP réussie');
			} else { // SINON on léve une exception
				throw new XException('00200001', 4, array(), true);
			}

		} catch (XException $Xe) { // FIN Try
			throw new XException('00200002', 4, array(0 => $Xe->getMessage()), true);
		} // FIN Catch
	}

	/**
	 *
	 * Déconnexion
	 *
	 **/
	public function disconnect() {

		try {
			//	déconnexion
			//global $ldapConnexion;
			ldap_close($this->_ldapConnexionStream);
			$this->_ldapConnexionStream = null;
			$this->_ldapConnexion				=	null;

		} catch (XException $Xe) { // FIN Try
			throw new XException('00200003', 4, array(), true);
		} // FIN Catch
	}

	/**
	 *
	 * Add an entry to an LDAP folder
	 *
	 * @params 		XLdap $XLdap
	 *
	 * @exception	XException
	 *
	 **/
	public function add(XLdap $XLdap)	{

		try{
			// Connexion au serveur LDAP
			//global $ldapConnexion;

			// Vérification de l'existance de l'utilisateur sur le serveur LDAP
			$user_check = self::search(array('mail' => $XLdap->mail(), 'strict' => 1));

// 			if ($user_check['result'] == 'success' && $user_check['message'] != false) { //si l'utilisateur est déjà présent

// 				// on récupère l'utilisateur
// 				$existing_user = $user_check['message'][0];

// 				if ($existing_user !== $XLdap) { // si l'utilisateur en base est différent de celui qu'on souhaite ajouter

// 					//on supprime l'utilisateur en base
// 					$current_user_to_delete = self::delete($existing_user, $adding_type);

// 					if ($current_user_to_delete['result'] == 'success') { // si la suppression a été effectuée

// 						// Ajout du nouvel utilisateur
// 						$ldap_adding = ldap_add($ldapConnexion, $XLdap->dn(), $XLdap->ldapObject());

// 						if ($ldap_adding || $ldap_adding == "Success") { // si l'ajout a correctement été effactué

// 							// création du message retourné
// 							$message = "Utilisateur ".$XLdap->mail()." correctement ajouté après suppression d'un utilisateur existant";
// 							// On retourne un message de confirmation
// 							return array("result" => "success", "message" => $message);

// 						} else { // l'ajout n'a pas été réalisé
// 							// on lève une exception
// 							throw new XException('00200004', 4, array( 0 => $XLdap->mail()), true);
// 						}
// 					} else { // la suppression n'a pas été réalisé
// 						// on lève une exception
// 						throw new XException('00200005', 4, array( 0 => $XLdap->mail()), true);
// 					}

// 				} else { // le npivel utilisateur et l'existant sont identique

// 					$message = "Utilisateur ".$XLdap->mail()." déjà existant et identique";
// 					// On retourne un message de confirmation
// 					return array("result" => "success", "message" => $message);

// 				}

// 			} else { //l'utilisateur n'existe pas en base

// 				// ajout du nouvel utilisateur
// 				$ldap_adding = ldap_add($ldapConnexion, $XLdap->dn(), $XLdap->ldapObject());

// 				if ($ldap_adding || $ldap_adding == "Success") { // SI l'utilisateur a été correctement ajouté

// 					$message = "Utilisateur ".$XLdap->mail()." correctement ajouté";
// 					// On retourne un message de confirmation
// 					return array("result" => "OK", "message" => $message);

// 				} else { //l'utilisateur a été correctement ajouté
// 					// on lève une exception
// 					throw new XException('00200004', 4, array( 0 => $XLdap->mail()), true);
// 				}
// 			}
		} catch (XException $Xe) { // FIN Try

			// Retour du message d'erreur
			return array("result" => "ERROR", "message" => $Xe->getMessage());

		} // FIN Catch
	}

	/**
	 *
	 * Effectue une recherche sur le serveur LDAP
	 *
	 * @param array $search : Tableau de clés/valeurs qui seront interprétés/recherchés
	 *
	 * @exception XException
	 *
	 * @return array : Tableau contenant le résultat et un message
	 *
	 */
	public function search ($search){

		try {

			// Récupération de la connexion au serveur LDAP
			//global $ldapConnexion;

			if (is_array($search)) { // Si le paramètre $search est un tableau

				// Dans le cas ou l'on veut une recherche strict
				if (isset($search['strict']) && $search['strict'] === 1) {
					$strict_search = '';
				} else {
					//$strict_search = '*';
				   $strict_search = '';
				}

				// init le filtre de recherche
				$ldap_filter = "";

				foreach ($search as $search_key => $search_value) {
					switch ($search_key) {
						case 'cn': 					$ldap_filter .= "(cn=".$strict_search.$search_value.$strict_search.")";					break;
						case 'sn': 					$ldap_filter .= "(sn=".$strict_search.$search_value.$strict_search.")";					break;
						case 'uid': 				$ldap_filter .= "(uid=".$strict_search.$search_value.$strict_search.")";				break;
						case 'dn': 					$ldap_filter .= "(dn=".$strict_search.$search_value.$strict_search.")";					break;
						case 'displayName': 		$ldap_filter .= "(displayName=".$strict_search.$search_value.$strict_search.")";		break;
						case 'givenName': 		$ldap_filter .= "(givenName=".$strict_search.$search_value.$strict_search.")";		break;
						case 'postalCode': 		$ldap_filter .= "(postalCode=".$strict_search.$search_value.$strict_search.")";		break;
						case 'l': 					$ldap_filter .= "(l=".$strict_search.$search_value.$strict_search.")";					break;
						case 'title': 				$ldap_filter .= "(title=".$strict_search.$search_value.$strict_search.")";				break;
						case 'employeeNumber': 	$ldap_filter .= "(employeeNumber=".$strict_search.$search_value.$strict_search.")";	break;
						case 'mail': 				$ldap_filter .= "(mail=".$strict_search.$search_value.$strict_search.")";				break;
					}
				}

				$ldap_filter = "(&".$ldap_filter.")";

				// recherche LDAP
				//var_dump($ldapConnexion);
				//var_dump(self::$medimail_ldap_base);
				var_dump($ldap_filter);
				$searching = ldap_search($this->_ldapConnexionStream, self::$medimail_ldap_base, $ldap_filter);

				// résultat de la recherche
				$info = ldap_get_entries($this->_ldapConnexionStream, $searching);

				// Si il n'y a oas de résultat
				if ($info['count'] == 0) {
					return array("result" => "Error", "message" => false);
				}

				$result = array();

				for ($i = 0; $i < $info['count']; $i++)
				{
				   $current_result = new XLdap($info[$i]);
				   $result[] = $current_result;
				}

				// retour du resultats
				return array("result" => "Success", "message" => $result);

			}

		} catch (XException $Xe) { // FIN Try
			// Retour du message d'erreur
			return array("result" => "ERROR", "message" => $Xe->getMessage());
		} // FIN Catch
	}

	/**
	 *
	 * Supprime un utilisateur sur le serveur LDAP
	 *
	 * @param XLdap $XLdap : user LDAP
	 *
	 * @exception XException
	 *
	 * @return array : Tableau contenant le résultat et un message
	 *
	 **/
	public function delete(XLdap $XLdap)	{

		try{

			// Récupération de la connexion au serveur LDAP
			//global $ldapConnexion;

			// Vérification de l'existance de l'utilisateur sur le serveur LDAP
			$user_check = self::search(array('mail' => $XLdap->mail()), $user_type);

			if ($user_check['result'] == 'success' && $user_check['message'] != false) { // si l'utilisateur existe
				// Ajout de l'utilisateur
				$ldap_deleting = ldap_delete($this->_ldapConnexionStream, $XLdap->dn());
			} else { // l'utilisateur n'existe pas
				// On lève une exception
				throw new XException('00200007', 4, array( 0 => $XLdap->mail()), true);
			}

			// SI l'utilisateur a été correctement ajouté
			if ($ldap_deleting || $ldap_deleting === "Success") {

				$message = "Utilisateur ".$XLdap->mail()." correctement supprimé";
				// On retourne un message de confirmation
				return array("result" => "success", "message" => $message);
			} else { // la suppression n'a pas été réalisé
				// On lève une exception
				throw new XException('00200008', 4, array( 0 => $XLdap->mail()), true);
			}

		} catch (XException $Xe) { // FIN Try
			// Retour du message d'erreur
			return array("result" => "ERROR", "message" => $Xe->getMessage());
		} // FIN Catch
	}

	/**
	 *
	 * Modifie un utilisateur sur le serveur LDAP
	 *
	 * @param XLdap $XLdap : user LDAP
	 *
	 * @exception XException
	 *
	 * @return array : Tableau contenant le résultat et un message
	 *
	 **/
	public function update(XLdap $XLdap)	{

		try {

			// Récupération de la connexion au serveur LDAP
			//global $ldapConnexion;

			// Vérification de l'existance de l'utilisateur sur le serveur LDAP
			$user_check = self::search(array('mail' => $XLdap->mail()));

			if ($user_check['result'] == 'success' && $user_check['message'] != false) {

				// Ajout de l'utilisateur
				$ldap_modifying = ldap_modify($this->_ldapConnexionStream, $XLdap->dn(), $XLdap->ldapObject());

			} else {
				// On lève une exception
				throw new XException('00200007', 4, array( 0 => $XLdap->mail()), true);
			}

			if ($ldap_modifying || $ldap_modifying == "Success") {// SI l'utilisateur a été correctement modifié...

				$message = "Utilisateur ".$XLdap->mail()." correctement modifié";
				// On retourne un message de confirmation
				return array("result" => "success", "message" => $message);

			} else {
				// On lève une exception
				throw new XException('00200009', 4, array( 0 => $XLdap->mail()), true);
			}

		} catch (XException $Xe) { // FIN Try
			// Retour du message d'erreur
			return array("result" => "ERROR", "message" => $Xe->getMessage());
		} // FIN Catch

	}

	/**
	 *
	 * fonction permettant le parse du fichier ldif
	 *
	 * @param string $fichier 	nom du fichier ldif
	 *
	 * @exception XException
	 *
	 * @return array tableau contenant chacune des entrées du fichier ldif
	 *
	 */
	public function parseLdifDoc($fichier){

		try {

			if (file_exists($fichier)){ //si le fichier existe
				// on ouvre le fichier
				$lecture_fichier = fopen($fichier,"r");
			} else {
				// on lève une exception
				throw new XException('00200010', 4, array( 0 => $fichier), true);
			}

			if (!empty($lecture_fichier)){// si le fichier a été ouvert

				$results = array();
				$fullResults = array();
				$objectClassArray = array();
				$this->nb_entries = 0;

				while (($buffer = fgets($lecture_fichier, 4096)) !== false) {

					// Explode avec 2x2points pour vérifier si il s'agit d'une chaîne encodée en base64
					$bufferKeyVal = explode(":: ", $buffer);

					// SI la ligne n'est pas vide...
					if (trim($bufferKeyVal[0]) !== "") {
						// SI il existe une valeur encodée en base64...
						if ( isset($bufferKeyVal[1])){
							$key = $bufferKeyVal[0];											// On récupère la clé
							$val = base64_decode($bufferKeyVal[1]);				// On décode le contenu
						} else {	// SINON...
							$bufferKeyValSingle = explode(":", $buffer);	// On retravaille la chaîne initiale
							$key = $bufferKeyValSingle[0];								// On récupère la clé
							$val = trim($bufferKeyValSingle[1]);					// On récupère la valeur
						}

						// SI la clé est objectClass..
						if ($key === 'objectClass') {
							// on ajoute la valeur au tableau
							$objectClassArray[] = $val;
						} else {
							// ajout du couple key/val dans le tableau
							$results[$key] = $val;
						}

					} else {
						// Ajout de l'objectClass au tableau de $results
						$results['objectClass'] = $objectClassArray;

						// Reinit $objectClassArray
						$objectClassArray = null;

						if (!empty($results)){					// Pour chaque entrée de l'annuaire, rajout au tableau final
					      $fullResults[] = $results;

					      $this->nb_entries++;
						}

						// reint du tableau de résultat pour une entrée
						$results = Array();
					}
				}

				if (!feof($lecture_fichier)) {
					throw new XException('00200012', 4, array( 0 => $fichier), true);
				}


				// on ferme le fichier
				fclose($lecture_fichier);

				// retour du message de succes avec le tableau contenant les datas
				return array("result" => "success", "message" => $fullResults);

			} else {
				// on lève une exception
				throw new XException('00200011', 4, array( 0 => $finalyResults), true);
			}

		} catch (XException $Xe) { // FIN Try
			// retour du message d'erreur
			return array("result" => "ERROR", "message" => $Xe->getMessage());
		} // FIN Catch

	}

	/**
	 *
	 * fonction permettant le lister les utilisateurs du serveur LDAP
	 *
	 * @exception XException
	 *
	 * @return array tableau contenant les utilisateurs
	 *
	 */
	public function getUsersList(){

	   try {

	      // Récupération de la connexion au serveur LDAP
	      //global $ldapConnexion;

	      $search = ldap_search($this->_ldapConnexionStream, $this->_ldapConnexion->base(), $this->_ldapConnexion->search(), $this->_ldapConnexion->attributes());

	      $this->nb_entries = ldap_count_entries($this->_ldapConnexionStream, $search);

	      //get the entries from the result
	      //we are not going to use ldap_get_entries as its got a limit of 1000
	      $entries = array();

	      // boucle sur toutes les entrees
	      for(  $entry = ldap_first_entry($this->_ldapConnexionStream, $search);
              $entry != false;
              $entry = @ldap_next_entry($this->_ldapConnexionStream, $entry))
	      {
	         //new entry, new array
	         $entries[] = array();

	         // recupere tous les attribus
	         $attributes = ldap_get_attributes($this->_ldapConnexionStream, $entry);

	         // construction du tableau attribu => valeur
	         foreach($attributes as $name=>$value) {
	            if(is_array($value) && $value['count']>0) {
	               unset($value['count']); //we do not want the count really
	               $entries[count($entries)-1][$name] = $value[0];
	            }
	         }

	         // ajout de l'attribu dn
	         $entries[count($entries)-1]['dn'] = ldap_get_dn($this->_ldapConnexionStream, $entry);
	      }

        //var_dump($entries);

	      // retour du message de succes avec le tableau contenant les datas
	      return array("result" => "success", "message" => $entries);

	   } catch (XException $Xe) { // FIN Try
	      // retour du message d'erreur
	      return array("result" => "ERROR", "message" => $Xe->getMessage());
	   } // FIN Catch

	}

	/**
	 *
	 * Retourne le nombre d'enregistrements effectues
	 *	 *
	 * @return nb_entries
	 */
	public function getNbEntrie() {

	   return $this->nb_entries;
	}

/************************************************************/
/*****************     FIN FONCTIONS     ********************/
/************************************************************/

}

?>

<?php
/*************************************
 * @project:  Xenomorph - Models - XMailer
 * @file:   MODEL CLASS XMailer
 * @author:   Mickaël POLLET
 *************************************/

class XMailer extends XClass
{

/******************************************************/
/*****************     PARAMETRES     *****************/
/******************************************************/

	//use XSystem;

	// Propriétés par défaut
  private $_priority = 3;

  // Déclaration des propriétés
  public function setClassProperties() {
		$this->property('author',          		'XUser');    // Auteur du mail
    $this->property('replyTo',            'string');   // Adresse de réponse du mail si celle-ci est différente de l'auteur original
    $this->property('signatories',        'array');    // Tableau des XUsers destinataires. Il faut utiliser la méthode 'addSignatory' pour ajouter un ou plusieurs destinataires
    $this->property('recipients',         'array');    // Tableau des XUsers destinataires en copie. Il faut utiliser la méthode 'addRecipient' pour ajouter un ou plusieurs destinataires
    $this->property('hiddenRecipients',   'array');    // Tableau des XUsers destinataires en copie cachés. Il faut utiliser la méthode 'addHiddenRecipient' pour ajouter un ou plusieurs destinataires
    $this->property('subject',            'string');   // Objet du mail
    $this->property('bodyHtml',           'string');   // Version HTML du corps du mail
    $this->property('bodyTxt',            'string');   // Version texte du corps du mail
    $this->property('attachments',        'string');   // Pièces jointes du mail
		$this->property('specificHeaders',    'string');   // Header spécifiques à ajouter à l'envoi du mail
		$this->property('priority',           'integer');  // Importance de priorité du mail
  }

/**********************************************************/
/*****************     FIN PARAMETRES     *****************/
/**********************************************************/


/********************************************************/
/*****************     CONSTRUCTEUR     *****************/
/********************************************************/

	public function __construct(array $mailer_datas = array()) {	// Constructeur dirigé vers la méthode d'hydratation
		parent::__construct($mailer_datas);
		$this->setSpecificHeaders();
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

	/***************** SETTERS PRIVES *****************/
	// Setter chargé d'affecter le tableau des destinataires du mail
	private function setSignatories(array $signatories) {
		$signatories_to_set = array();
		foreach ($signatories as $signatories_key => $signatories_value) {
			$signatories_to_set[] = $signatories_value;
		}
		parent::setSignatories($signatories_to_set);
	}

	// Setter chargé d'affecter le nom de l'application
	private function setRecipients(array $recipients) {
		$recipients_to_set = array();
		foreach ($recipients as $recipients_key => $recipients_value) {
			$recipients_to_set[] = $recipients_value;
		}
    parent::setRecipients($recipients_to_set);
	}

  // Setter chargé d'affecter le nom de l'application
	private function setHiddenRecipients(array $hiddenRecipients) {
		$hiddenRecipients_to_set = array();
		foreach ($hiddenRecipients as $hiddenRecipients_key => $hiddenRecipients_value) {
			$hiddenRecipients_to_set[] = $hiddenRecipients_value;
		}
    parent::setHiddenRecipients($hiddenRecipients_to_set);
	}

	private function setAttachments(array $attachments) {
		$attachments_to_set = array();
		foreach ($attachments as $attachments_key => $attachments_value) {
			$attachments_to_set[] = $attachments_value;
		}
    parent::setAttachments($attachments_to_set);
	}

	private function organizeActors($actors, $domain) {

		$actors_domain = array('signatories', 'recipients', 'hiddenRecipients');
		//if (!in_array($domain, $actors_domain)) {	throw new XException('00030005', 4, array( 0 => $domain));	}

		switch ($domain) {
			case 'signatories':		   $current_actors = $this->signatories();       break;	// Récupération des signatories déjà présents
			case 'recipients':		   $current_actors = $this->recipients();        break;	// Récupération des recipients déjà présents
			case 'hiddenRecipients': $current_actors = $this->hiddenRecipients();  break;	// Récupération des hiddenRecipients déjà présents
		}

		$mails_list = array();		// Création de la liste tampon de mails
		$actors_to_set = array();	// Création de la liste des nouveaux destinataires à ajouter
		$global_actors = array();	// Création de la liste globale des destinataires

		// Parcours des destinataires déjà présents
		if ($current_actors != null) {
			foreach ($current_actors as $current_signatories_key => $current_signatories_value) {
				if ($current_signatories_value->mail() != null) {
					$mails_list[] = $current_signatories_value->mail();
					$global_actors[] = $current_signatories_value;
				}
			}
		}

		// SI le destinataire envoyé est une chaîne de caractères, donc un mail ou une suite de mails...
		if (is_string($actors)) {
			$actors_to_set_string = explode(';', $actors);
			$actors_to_set_string = array_unique($actors_to_set_string);

			foreach ($actors_to_set_string as $actors_to_set_string_key => $actors_to_set_string_value) {
				if (checkMailAddress($actors_to_set_string_value)) {
					$actor_to_set = new XUser();
					$actor_to_set->setMail($actors_to_set_string_value);
					if (!in_array($actors_to_set_string_value, $mails_list)) {
						$mails_list[] = $actors_to_set_string_value;
						$actors_to_set[] = $actor_to_set;
					}
				} else {
					//throw new XException('00030004', 4, array( 0 => $actors_to_set_string_value));
				}
			}
		} else if (is_array($actors)) {  // SINON SI le destinataire envoyé est un tableau...

			// SI il y a plusieurs tableaux de destinataires...
			if (isset($actors[0]) && is_a($actors[0], 'XUser')) {
				foreach ($actors as $actors_to_set_array_key => $actors_to_set_array_value) {
					if (checkMailAddress($actors_to_set_array_value->mail())) {
						if (!in_array($actors_to_set_array_value->mail(), $mails_list)) {
							$mails_list[] = $actors_to_set_array_value->mail();
							$actors_to_set[] = $actors_to_set_array_value;
						}
					} else {
						//throw new XException('00030004', 4, array( 0 => $actors_to_set_array_value->mail()));
					}
				}
			} else {  // SINON il n'y a qu'un seul destinataire...
			//	if (!is_a($actors, 'Xuser')) {	throw new XException('00030007', 4);	}
				if (checkMailAddress($actors->mail())) {
					if (!in_array($actors->mail(), $mails_list)) {
						$mails_list[] = $actors->mail();
						$actors_to_set[] = $actors;
					}
				} else {
					//throw new XException('00030004', 4, array( 0 => $actors_to_set_array_value->mail()));
				}
			}
		} else if (is_a($actors, 'Xuser')) {
			if (checkMailAddress($actors->mail())) {
				if (!in_array($actors->mail(), $mails_list)) {
					$mails_list[] = $actors->mail();
					$actors_to_set[] = $actors;
				}
			} else {
				//throw new XException('00030004', 4, array( 0 => $actors_to_set_array_value->mail()));
			}
		}

		foreach ($actors_to_set as $actors_to_set_key => $actors_to_set_value) {
			$global_actors[] = $actors_to_set_value;
		}

		switch ($domain) {
			case 'signatories':			$this->setSignatories($global_actors);		break;
			case 'recipients':			$this->setRecipients($global_actors);		break;
			case 'hiddenRecipients':	$this->setHiddenRecipients($global_actors);	break;
		}
	}

	/***************** SETTERS PUBLIQUES *****************/
	// Setter chargé d'affecter le nom de l'application
	public function setAuthor($author) {
		if (is_string($author)) {
			if (checkMailAddress($author)) {
				$author_to_set = new XUser();
				$author_to_set->setMail($author);
			} else {
				//throw new XException('00030004', 4, array( 0 => $author));
			}
		} else {
			if (is_a($author, 'XUser')) {
				$author_to_set = $author;
			} else {
			//	throw new XException('00030007', 4);
			}
		}
    parent::setAuthor($author_to_set);
	}

	public function setReplyTo($replyTo) {

		if (is_string($replyTo)) {

			if (checkMailAddress($replyTo)) {
				$replyTo_to_set = new XUser();
				$replyTo_to_set->setMail($replyTo);
			} else {
			//	throw new XException('00030004', 4, array( 0 => $replyTo));
			}
		} else {
			if (is_a($replyTo, 'XUser')) {
				$replyTo_to_set = $replyTo;
			} else {
			//	throw new XException('00030007', 4);
			}
		}
    parent::setReplyTo($replyTo_to_set);
	}

	public function addSignatory($signatory) {
		$this->organizeActors($signatory, 'signatories');
	}

	public function addRecipient($recipient) {
		$this->organizeActors($recipient, 'recipients');
	}

	public function addHiddenRecipient($hiddenRecipient) {
		$this->organizeActors($hiddenRecipient, 'hiddenRecipients');
	}

  public function setSignatory($signatory) {
    $this->addSignatory($signatory);
  }

  public function setRecipient($recipient) {
    $this->addRecipient($recipient);
  }

  public function setHiddenRecipient($hiddenRecipient) {
    $this->addHiddenRecipient($hiddenRecipient);
  }

  // Setter chargé d'affecter le nom de l'application
	public function setSubject($subject) {
		//if (empty($subject) || !is_string($subject)) {	throw new XException('00030001', 4, array( 0 => $subject));	}
    parent::setSubject(decodingDatas($subject));
	}

	// Setter chargé d'affecter le BodyHtlm de l'application
	public function setBodyHtml($bodyHtml, $strict = false) {

		//if (empty($bodyHtml) || !is_string($bodyHtml)) {	throw new XException('00030002', 4, array( 0 => $bodyHtml));	}

		if ($strict === true) {
      parent::setBodyHtml($bodyHtml);
		} else {
      parent::setBodyHtml(decodingDatas($bodyHtml));
		}
	}

	// Setter chargé d'affecter le BodyTxt de l'application
	public function setBodyTxt($bodyTxt = null, $strict = false) {

		//if (!is_string($bodyTxt) && $bodyTxt != null) {	throw new XException('00030003', 4, array( 0 => $bodyTxt));	}

		if ($bodyTxt == null) {
			$bodyTxt = strip_tags($this->bodyHtml());
		}

		if ($strict === true) {
      parent::setBodyTxt(securiseDatas($bodyTxt));
		} else {
      parent::setBodyTxt(securiseDatas(decodingDatas($bodyTxt)));
		}
	}

  public function setBody($body = null) {
    $this->setBodyHtml($body);
    $this->setBodyTxt($body);
  }

	public function setSpecificHeaders(array $specificHeaders = null) {

		//$appSpecificHeaders = globalConfig('xmailer_specific_headers');
    $appSpecificHeaders = array();

		$specificHeadersToAdd = array();

		foreach ($appSpecificHeaders as $appSpecificHeaders_key => $appSpecificHeaders_value) {
			$specificHeadersToAdd[] = array(decodingDatas($appSpecificHeaders_key) => decodingDatas($appSpecificHeaders_value));
		}

		if ($specificHeaders != null) {

			foreach ($specificHeaders as $specificHeaders_key => $specificHeaders_value) {
				$specificHeadersToAdd[] = array(decodingDatas($specificHeaders_key) => decodingDatas($specificHeaders_value));
			}

		}
		$this->_specificHeaders = $specificHeadersToAdd;
	}

	public function addAttachment($attachment) {

		$current_attachments = $this->_attachments;		// Récupération des attachment déjà présents

		$attachments_list = array();					// Création de la liste tampon de mails
		$attachments_to_set = array();					// Création de la liste des nouveaux attachment à ajouter
		$global_attachments = array();					// Création de la liste globale des attachment

		// Parcours des destinataires déjà présents
		if ($current_attachments != null) {
			foreach ($current_attachments as $current_attachments_key => $current_attachments_value) {
				if ($current_attachments[$current_attachments_key]->path() != null) {
					$attachments_list[] = $current_attachments[$current_attachments_key]->path();
					$global_attachments[] = $current_attachments[$current_attachments_key];
				}
			}
		}

		if (is_a($attachment, 'XAttachment')) {
			if (!in_array($attachment->path(), $attachments_list)) {
				$attachments_to_set[] = $attachment;
			}
		} else if (is_array($attachment)) {
			foreach ($attachment as $attachment_key => $attachment_value) {
				if (is_a($attachment_value, 'XAttachment')) {
					if (!in_array($attachment_value->path(), $attachments_list)) {
						$attachments_list[] = $attachment_value->path();
						$attachments_to_set[] = $attachment_value;
					}
				} else {
					throw new XException('00030006', 4);
				}
			}
		} else {
			throw new XException('00030006', 4);
		}

		foreach ($attachments_to_set as $attachments_to_set_key => $attachments_to_set_value) {
			$global_attachments[] = $attachments_to_set[$attachments_to_set_key];
		}

		$this->setAttachments($global_attachments);
	}

	public function setPriority(int $priority) {
		switch ($priority) {
			case 1:		parent::setPriority(1); break;
			case 3:		parent::setPriority(3); break;
			case 5:		parent::setPriority(5); break;
			default:	parent::setPriority(1); break;
		}
	}

/*******************************************************/
/*****************     FIN SETTERS     *****************/
/*******************************************************/

/*******************************************************/
/*****************      FONCTIONS       ****************/
/*******************************************************/
/*******************************************************/
/*****************      FONCTIONS       ****************/
/*******************************************************/
}
?>

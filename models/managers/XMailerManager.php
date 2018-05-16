<?php
/*************************************
 * @project:  Xenomorph
 * @file:   MANAGER de la Class XMailer
 * @author:   Mickaël POLLET
 *************************************/

//require_once(SITE_ROOT.LIBS_DIR.'PHPMailer-master/class.phpmailer.php');
//require_once(SITE_ROOT.LIBS_DIR.'PHPMailer-master/class.smtp.php');

class XMailerManager
{
/******************************************************/
/*****************     PARAMETRES     *****************/
/******************************************************/

  use XSystem;

/**********************************************************/
/*****************     FIN PARAMETRES     *****************/
/**********************************************************/


/********************************************************/
/*****************     CONSTRUCTEUR     *****************/
/********************************************************/

  public function __construct() {}

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
/*********************     FONCTIONS     ********************/
/************************************************************/

public function send(XMailer $XMail, $debug = 0) {

    try {

        $mail = new PHPMailer(true);

        // Informations de connexion
        //$mail->setLanguage('fr', SITE_ROOT.LIBS_DIR.'PHPMailer-master/language/');
        $mail->IsSMTP();
        $mail->SMTPDebug  = $debug;
        $mail->Host       = globalConfig('xmailer', 'host');
        $mail->Port       = globalConfig('xmailer', 'port');
        // Activation du TLS si il est activé dans le config.ini
        if (globalConfig('xmailer', 'tls') == "1") {
          $mail->SMTPAuth   = true;
          $mail->SMTPSecure = "tls";
          $mail->SMTPOptions = array(
              'ssl' => array(
                  'verify_peer' => false,
                  'verify_peer_name' => false,
                  'allow_self_signed' => true
              )
          );
        }
        $mail->Username   = globalConfig('xmailer', 'username');
        $mail->Password   = globalConfig('xmailer', 'password');

        // Gestion des Headers Spécifiques
        foreach ($XMail->specificHeaders() as $specificHeaders_key => $specificHeaders_value) {
          $specificHeader = $specificHeaders_value;
          foreach ($specificHeader as $specificHeader_key => $specificHeader_value) {
            $mail->addCustomHeader($specificHeader_key, $specificHeader_value);
          }
        }

        // Gestion de l'auteur
        $author = $XMail->author();
        if ($author->fname() != '' && $author->lname() != '') {
          $mail->SetFrom(decodingDatas($author->mail()), decodingDatas($author->fname()).' '.decodingDatas($author->lname()));
        } else {
          $mail->SetFrom(decodingDatas($author->mail()));
        }

        // Gestion du ReplyTo
        if ($XMail->replyTo() != null) {
          $replyTo = $XMail->replyTo();
          if ($replyTo->fname() != '' && $replyTo->lname() != '') {
            $mail->AddReplyTo(decodingDatas($replyTo->mail()), decodingDatas($replyTo->fname()).' '.decodingDatas($replyTo->lname()));
          } else {
            $mail->AddReplyTo(decodingDatas($replyTo->mail()));
          }
        }

    // Ajout des destinataires
        if ($XMail->signatories() != null) {
          foreach ($XMail->signatories() as $signatories_key => $signatories_value) {
            $signatory = $signatories_value;
            if ($signatory->fname() != '' && $signatory->lname() != '') {
              $mail->AddAddress(decodingDatas($signatory->mail()), decodingDatas($signatory->fname()).' '.decodingDatas($signatory->lname()));
            } else {
              $mail->AddAddress(decodingDatas($signatory->mail()));
            }
          }
        }

        // Gestion des destinataires en copie
        if ($XMail->recipients() != null) {
          foreach ($XMail->recipients() as $recipients_key => $recipients_value) {
            $recipient = $recipients_value;
            if ($recipient->fname() != '' && $recipient->lname() != '') {
              $mail->AddCC(decodingDatas($recipient->mail()), decodingDatas($recipient->fname()).' '.decodingDatas($recipient->lname()));
            } else {
              $mail->AddCC(decodingDatas($recipient->mail()));
            }
          }
        }

        // Gestion des destinataires en copie cachée
        if ($XMail->hiddenRecipients() != null) {
          foreach ($XMail->hiddenRecipients() as $hiddenRecipients_key => $hiddenRecipients_value) {
            $hiddenRecipient = $hiddenRecipients_value;
            if ($hiddenRecipient->fname() != '' && $hiddenRecipient->lname() != '') {
              $mail->AddBCC(decodingDatas($hiddenRecipient->mail()), decodingDatas($hiddenRecipient->fname()).' '.decodingDatas($hiddenRecipient->lname()));
            } else {
              $mail->AddBCC(decodingDatas($hiddenRecipient->mail()));
            }
          }
        }

        $mail->Subject = $XMail->subject();           // Ajout du sujet
        if ($XMail->bodyHtml() == null) {            // Vérification du Body TXT
          $XMail->setBodyHtml(strip_tags($XMail->bodyTxt()));
        }
        $mail->MsgHTML($XMail->bodyHtml());           // Ajout du Body HTML
        if ($XMail->bodyTxt() == null) {            // Vérification du Body TXT
          $XMail->setBodyTxt(strip_tags($XMail->bodyHtml()));
        }
        $mail->AltBody = $XMail->bodyTxt();           // Ajout du Body TXT
        $mail->Priority = $XMail->priority();         // Gestion de la priorité

        // Ajout des pièces jointes
        if ($XMail->attachments() != null) {
          foreach ($XMail->attachments() as $attachments_key => $attachments_value) {
            $mail->AddAttachment( $attachments_value->path(),
                                  $attachments_value->name(),
                                  $attachments_value->encoding(),
                                  $attachments_value->type());
          }
        }

        $mail->Timeout = globalConfig('xmailer', 'timeout');  // Gestion du timeout

        $mail->Send();

    } catch (phpmailerException $e) {
      throw new Exception($e->errorMessage());
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }

  }

/************************************************************/
/*****************     FIN FONCTIONS     ********************/
/************************************************************/

}

?>

<?php

require_once 'PdoConnect.php';
require 'wamailer-dev/mailer.class.php';

class MailManager extends PdoConnect {

    public function contactMail($email, $commentaire) {

        global $adminAdresse;

        $title = "Message de contact de votre site Web";

        $contenuEmail = "
            <h3><span style='color: #0c770c'>Vous venez de Recevoir une demande de Contact.</span></h3>\n<br/>
            \n<br/>\n<br/><span style='color: #514f4f'>
            <h4>Expediteur:</h4>\n<br/>
            <b>
            Email: " . $email . "\n<br/>
            \n<br/>
            Message:</b></span>\n<br/><span style='color: #807173'><b>
            " . $commentaire . "</b></span>";

        $mailer = new Email();
        $mailer->setFrom($email, "Anonyme");
        $mailer->addRecipient("contact@tradinov-sas.fr");
        $mailer->setSubject($title);
        $mailer->setHTMLBody(utf8_decode(stripslashes($contenuEmail)));

        Mailer::send($mailer);
    }

    public function recupMail($login, $webSiteUrl, $uniqueID) {
        
        $title = "Demande de réinitialisation de mot de passe";
        
        $contenuEmail = "
            <h2 style='color: red;'>Vous souhaitez réinitialiser votre mot de passe</h2>
            
            <br/><br/>
            
            <div style='color: red;'>ATTENTION</div> si vous n'avez pas fait un telle demande, cela signifie que quelqu'un tente d'entrer sur votre session !<br/>
            <i>Dans ce cas, veuillez contacter le webmaster.</i></div>
            
            <br/><br/>
            
            Dans le cas contraire, veuillez simplement suivre ce lien : <br/><br/>
            
            ".$webSiteUrl."&email=".$login."&ui=".$uniqueID."  <br/><br/>
                
            Il vous sera ensuite demandé de choisir votre nouveau mot de passe.";
        
        $mailer = new Email();
        $mailer->setFrom("contact@argetweb.fr", "Webmaster - Reinitialisation MDP");
        $mailer->addRecipient($login);
        $mailer->setSubject(utf8_decode(stripslashes($title)));
        $mailer->setHTMLBody($contenuEmail);

        Mailer::send($mailer);
        
    }

}

?>
<?php

    

?>


<div id="home">
    
    <div class="image">
        <img src="Web/img/bibliotheque/logo.png" alt="Suite-Logique"/>
    </div>
    <br/>
    <div id="deconnexion">
        <img src="Web/img/bibliotheque/door_out.png" alt="deconnexion" />&nbsp;<a href="Web/views/logout.php" title="Se d&eacute;connecter">Se d&eacute;connecter</a>
    </div>
    
    <div id="general">
        
        <h2>Bienvenue <?php echo $userAdmin->prenom; ?> dans votre gestionnaire</h2>

        <br/>
        <br/>
        <?php if($userAdmin->privilege < 9){ ?>

            <i>Votre niveau d'administration ne vous permet pas l'accès à toutes les fonctionnalités.<br/>
                Vous pouvez néammoins administrer le site internet via le menu de navigation à gauche</i>

        <?php }else{ ?>

        <i> En tant que WebMaster, vous pouvez:</i><br/><br/>
        &nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<a href="index.php?content=admin" title="gerer les utilisateurs">Gérer les administrateurs</a><br/>
        &nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<a href="index.php?content=logs" title="consulter les logs">Consulter les logs</a>


        <?php
        }
            if($ie7 || $ie8){

                echo "<br/><br/><span style='color:red; font-size: 20px;'>Attention, votre navigateur n'est pas &agrave; jour<br/>
                Certaines fonctionnalit&eacute;s de ce site ne marcheront certainement pas...<br/>
                Pensez &agrave; le mettre &agrave; jour ici: <br/>
                <a href='http://windows.microsoft.com/fr-FR/internet-explorer/downloads/ie-9/worldwide-languages' title='Download IE' target='_blank' >Telecharger sur le site de Microsoft</a></span><br/><br/>";

            }

        ?>
        
    </div>
    
    <div class="developper">
        <a href="http://www.suite-logique.fr" target="_blank" title="Site web de votre prestataire"><i>Copyright - Suite-Logique 2012</i></a><br/>
    </div>
    <br/>
        
</div>
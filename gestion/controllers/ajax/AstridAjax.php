<?php

use Entities\Univers,
    Entities\TypeAnnonce,
    Entities\Annonce,
    Entities\LivreOr;

/**
 * 
 * Controller par defaut
 * Le controller doit absolument heriter de AjaxManager
 */
class AstridAjax extends AjaxManager {

    /**
     * Affichage de la page d'éditions d'univers
     * 
     * @return view
     */
    protected function universGestionController() {

        $objUnivers = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objUnivers = $this->em->getRepository('Entities\Univers')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objUnivers)) {
                $objUnivers = new Univers;
            }

            $objUnivers->setNom($nom);
            $objUnivers->setCodeCegid($code_cegid);
            $this->em->persist($objUnivers);
            $this->em->flush();
        } else {
            $this->renderView('views/astrid/univers-gestion.html.twig', array(
                'objUnivers' => $objUnivers
            ));
        }
    }

    /**
     * Affichage de la page d'éditions d'univers
     * 
     * @return view
     */
    protected function typeAnnonceGestionController() {

        $objTypeAnnonce = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objTypeAnnonce = $this->em->getRepository('Entities\TypeAnnonce')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objTypeAnnonce)) {
                $objTypeAnnonce = new TypeAnnonce;
            }

            $objTypeAnnonce->setNom($nom);
            $this->em->persist($objTypeAnnonce);
            $this->em->flush();
        } else {
            $this->renderView('views/astrid/type-annonce-gestion.html.twig', array(
                'objTypeAnnonce' => $objTypeAnnonce
            ));
        }
    }

    /**
     * Affichage de la page d'éditions d'annonces
     * 
     * @return view
     */
    protected function annonceGestionController() {

        $objAnnonce = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objAnnonce = $this->em->getRepository('Entities\Annonce')->find($_POST['idItem']);
        }

        if (isset($_POST['titre'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objAnnonce)) {
                $objAnnonce = new Annonce;
                $objAnnonce->setDateDepot(new DateTime("now", new DateTimeZone('Europe/Warsaw')));
            }

            $objAnnonce->setTitre($titre);
            $objAnnonce->setClient($this->em->getRepository('Entities\Client')->find($client));
            $objAnnonce->setTypeAnnonce($this->em->getRepository('Entities\TypeAnnonce')->find($typeAnnonce));
            $objAnnonce->setTexte($texte);
            $this->em->persist($objAnnonce);
            $this->em->flush();
        } else {
            $listClients = $this->em->getRepository('Entities\Client')->findAll();
            $listTypesAnnonces = $this->em->getRepository('Entities\TypeAnnonce')->findAll();

            $this->renderView('views/astrid/annonce-gestion.html.twig', array(
                'objAnnonce' => $objAnnonce,
                'listClients' => $listClients,
                'listTypesAnnonces' => $listTypesAnnonces
            ));
        }
    }

    /**
     * Affichage de la page d'éditions d'annonces
     * 
     * @return view
     */
    protected function livreOrGestionController() {

        $objLivreOr = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objLivreOr = $this->em->getRepository('Entities\LivreOr')->find($_POST['idItem']);
        }

        if (isset($_POST['texte'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objLivreOr)) {
                $objLivreOr = new LivreOr;
                $objLivreOr->setDateDepot(new DateTime("now", new DateTimeZone('Europe/Warsaw')));
            }

            $objLivreOr->setClient($this->em->getRepository('Entities\Client')->find($client));
            $objLivreOr->setTexte($texte);
            $this->em->persist($objLivreOr);
            $this->em->flush();
        } else {
            $listClients = $this->em->getRepository('Entities\Client')->findAll();

            $this->renderView('views/astrid/livre-or-gestion.html.twig', array(
                'objLivreOr' => $objLivreOr,
                'listClients' => $listClients
            ));
        }
    }

    /**
     * Affichage de la page d'éditions d'univers
     * 
     * @return view
     */
    protected function clientsGestionController() {

        $objClient = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objClient = $this->em->getRepository('Entities\Client')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objClient)) {
                $objClient = new \Entities\Client;
                $objClient->setDateCreation(new DateTime('@' . time(), NULL));
                $objClient->setPassword(FwkSecurity::encryptPassword($password));
            } else
            if ($password != 'password')
                $objClient->setPassword(FwkSecurity::encryptPassword($password));

            $objClient->setCivilite($civilite);
            $objClient->setNom($nom);
            $objClient->setPrenom($prenom);
            $objClient->setTelephone($telephone);
            $objClient->setTelephone2($telephone2);
            $objClient->setEmail($email);
            $this->em->persist($objClient);
            $this->em->flush();
        } else {
            $this->renderView('views/astrid/clients-gestion.html.twig', array(
                'objClient' => $objClient
            ));
        }
    }
    
    
    /**
     * Affichage de la page d'éditions d'univers
     * 
     * @return view
     */
    protected function newsletterGestionController() {

        $objNewsletter = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objNewsletter = $this->em->getRepository('Entities\Newsletter')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objNewsletter)) {
                $objNewsletter = new \Entities\Newsletter;
            }

            $objNewsletter->setAbonne($abonne);
            $objNewsletter->setNom($nom);
            $objNewsletter->setPrenom($prenom);
            $objNewsletter->setEmail($email);
            $this->em->persist($objNewsletter);
            $this->em->flush();
        } else {
            $this->renderView('views/astrid/newsletter-gestion.html.twig', array(
                'objNewsletter' => $objNewsletter
            ));
        }
    }
    
    
    /**
     * Affichage de la page d'éditions d'univers
     * 
     * @return view
     */
    protected function magasinGestionController() {

        $objMagasin = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objMagasin = $this->em->getRepository('Entities\Magasin')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objMagasin)) {
                $objMagasin = new \Entities\Magasin;
            }

            $objMagasin->setNom($nom);
            $objMagasin->setAdresse($adresse);
            $objMagasin->setAdresse2($adresse2);
            $objMagasin->setCp($cp);
            $objMagasin->setVille($ville);
            $objMagasin->setTel($tel);
            $objMagasin->setFax($fax);
            $objMagasin->setEmail($email);
            $objMagasin->setTexte($texte);
            $this->em->persist($objMagasin);
            $this->em->flush();
        } else {
            $this->renderView('views/astrid/magasin-gestion.html.twig', array(
                'objMagasin' => $objMagasin
            ));
        }
    }
    
    
    /**
     * Affichage de la page d'éditions d'annonces
     * 
     * @return view
     */
    protected function marqueGestionController() {

        $objMarque = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objMarque = $this->em->getRepository('Entities\Marque')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objMarque)) {
                $objMarque = new Entities\Marque;
            }
            
            $objMagasin = $this->em->getRepository('Entities\Magasin')->find($magasin);

            $objMarque->setNom($nom);
            $objMarque->setUrl($url);
            $objMarque->setCode(FwkUtils::urlAlize($code));
            $objMarque->setLogo($logo);
            $objMarque->setMagasin($objMagasin);
            $this->em->persist($objMarque);
            $this->em->flush();
        } else {
            $colMagasin = $this->em->getRepository('Entities\Magasin')->findAll();

            $this->renderView('views/astrid/marque-gestion.html.twig', array(
                'objMarque' => $objMarque,
                'listMagasins' => $colMagasin
            ));
        }
    }
    
    
    /**
     * Affichage de la page d'éditions d'annonces
     * 
     * @return view
     */
    protected function focusGestionController() {

        $objFocus = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objFocus = $this->em->getRepository('Entities\Focus')->find($_POST['idItem']);
        }

        if (isset($_POST['titre'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objFocus)) {
                $objFocus = new Entities\Focus;
            }
            
            $objMarque = $this->em->getRepository('Entities\Marque')->find($marque);

            $objFocus->setTitre($titre);
            $objFocus->setMarque($objMarque);
            $objFocus->setTexte($texte);
            $this->em->persist($objFocus);
            $this->em->flush();
        } else {
            $colMarque = $this->em->getRepository('Entities\Marque')->findAll();

            $this->renderView('views/astrid/focus-gestion.html.twig', array(
                'objFocus' => $objFocus,
                'colMarques' => $colMarque
            ));
        }
    }

}

?>

/*
 * 2012 ArgetFwk
 * 
 * FrameWork MVC en phase de développement et d'amélioration
 * 
 * Utilisation des FrameWorks :
 *  - TwitterBootstrap 2.1
 *  - Doctrine 2
 *  - Twig
 * 
 * -------------------
 * 
 *
 * @author Florian MITHIEUX <florian.mithieux@gmail.com>
 * @version  0.51a
 */

#############################################################################################

Pour faire fonctionner le FrameWork, merci de suivre ces étapes :


Ouvrez le fichier "app/config.php".
- Modifiez ici, toutes les informations essentielles de votre site.
- Modifiez en priorité les constantes concernant la "SOCIETE_NOM", "SITE_URL_BASE" et les informations concernant la base de données (à assigner en fonction de votre environnement)
             

Générez la base de données.
- Créez une base de données ayant pour nom celui que vous avez donné dans l'étape précédente.
- Suivez "apps/console", connectez-vous avec les informations présentes dans le fichier config.php (ADMIN_EMAIL et ADMIN_PASSWORD)
- Générez la base de données avec un premier enregistrement.

             
Accédez au Back-Office
- Suivez simplement ce lien : "gestion/"
- Visitez le Back Office comme il vous plaît.
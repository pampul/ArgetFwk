#ArgetFwk
-----------------


[ArgetFwk](http://florian-mithieux.fr/developpeur-web-lyon/portfolio-webmaster/10/argetfwk) est un framework MVC utilisant Doctrine 2 et Twig, adapté aux besoins de suite-logique, ainsi qu'aux clients.
Il a été développé durant mes heures personnelles, dans le but de me simplifier la vie.
Il a ensuite été amélioré durant le développement des nouveaux projets au sein de suite logique.
Ce framework est encore en cours d'amélioration et de refactoring.
Complet, ce framework a pour but de créer des back offices de sites internet très rapidement. De plus, il utilise le moteur de template Twig et possède une construction MVC, qui permet à tout développeur de comprendre le projet assez rapidement.

Sa création a été faite de façon à pouvoir optimiser le référencement de toutes les pages d'un site web. Il est en effet aisé de changer une H1, un title ou une description.



Enfin, une documentation technique est en cours d'écriture, et devrait pouvoir simplifier la vie de bon nombre de développeurs.

##Installation

Pour faire fonctionner le FrameWork, merci de suivre ces étapes :


Ouvrez le fichier `app/config.php`.
- Modifiez ici, toutes les informations essentielles de votre site.
- Modifiez en priorité les constantes concernant la `SOCIETE_NOM` et les informations concernant la base de données (à assigner en fonction de votre environnement)


Générez la base de données.
- Créez une base de données ayant pour nom celui que vous avez donné dans l'étape précédente.
- Allez sur `VOTREURL/apps/console`, connectez-vous avec les informations présentes dans le fichier `config.php` (`ADMIN_EMAIL` et `ADMIN_PASSWORD`)
- Cliquez sur générer la base de donnée avec un premier enregistrement.


Accédez au Back-Office
- Allez simplement sur ce lien : `VOTREURL/gestion/`
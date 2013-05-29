#ArgetFwk
-----------------

### Description

[ArgetFwk](http://florian-mithieux.fr/developpeur-web-lyon/portfolio-webmaster/10/argetfwk) est un framework MVC utilisant Doctrine 2 et Twig, adapté aux besoins de suite-logique, ainsi qu'aux clients.
Il a été développé durant mes heures personnelles, dans le but de me simplifier la vie.
Il a ensuite été amélioré durant le développement des nouveaux projets au sein de suite logique.
Ce framework est encore en cours d'amélioration et de refactoring.
Complet, ce framework a pour but de créer des back offices de sites internet très rapidement. De plus, il utilise le moteur de template Twig et possède une construction MVC, qui permet à tout développeur de comprendre le projet assez rapidement.

Sa création a été faite de façon à pouvoir optimiser le référencement de toutes les pages d'un site web. Il est en effet aisé de changer une H1, un title ou une description.


Enfin, une documentation technique est en cours d'écriture, et devrait pouvoir simplifier la vie de bon nombre de développeurs.




###Fonctionnalités principales du framework

- Framework MVC
- Framework utilisant l'ORM Doctrine et le moteur de template Twig
- Back Office intégré avec un module "CMS" permettant de créer des pages en fonction de templates
- Classes de génération de tableau de gestion en ajax pour back-end : construisez des modules gérables en back office très rapidement
- Routing très précis, obligant à fonctionner avec le .htaccess
- Module permettant d'update/remove/create la base de donnée directement en ligne
- Framework construis de façon à favoriser le référencement (les titles/descriptions et h1 sont à l'honneur)
- Gestion des sites multilingues




##Installation

Pour faire fonctionner le FrameWork, merci de suivre ces étapes :


Ouvrez le fichier `app/config.php`.
- Modifiez ici, toutes les informations essentielles de votre site.
- Modifiez en priorité les constantes concernant la `SOCIETE_NOM` et les informations concernant la base de données (à assigner en fonction de votre environnement)


Générez la base de données.
- Créez une base de données ayant pour nom celui que vous avez donné dans l'étape précédente.
- Allez sur `VOTREURL/apps/console`, connectez-vous avec les informations présentes dans le fichier `config.php` (`ADMIN_EMAIL` et `ADMIN_PASSWORD`)
Dans le cas où vous n'accédez pas à l'URL précédentes, vérifiez que votre IP est bien contenue dans le fichier `app/secured/.htaccess`.
- Cliquez sur générer la base de donnée avec un premier enregistrement.


Accédez au Back-Office
- Allez simplement sur ce lien : `VOTREURL/gestion/`



## Site multilingue

Vous pouvez gérer votre site de façon multilingue en modifiant simplement le `.htaccess`.
A l'emplacement de l'URL Rewriting, commentez les lignes de la première option, et décommentez les autres.
La langue est gérée grâce au paramètre `GET` envoyé par l'URL. Vous pouvez gérer vos traductions dans le dossier `app/translations`.
Chaque langue est disponible dès que le fichier `.php` correspondant est créé.

Enfin, afin de traduire un mot, c'est très simple. Vous avez juste à utiliser la fonction `translate` créée dans ce but.
Exemple : `{{ 'bienvenue'|translate }}`



## Librairies utilisées

  - [Doctrine 2 (ORM PHP)](http://www.doctrine-project.org/)
  - [Twig (Moteur de template PHP)](http://twig.sensiolabs.org/)
  - [SimpleHtmlDom (lib PHP de gestion de DOM)](http://simplehtmldom.sourceforge.net/)
  - [SwiftMailer (lib PHP pour mails)](http://swiftmailer.org/)
  - [WideImage (lib PHP de crop/resize/caching d'images)](http://wideimage.sourceforge.net/)
  - [TwitterBootstrap 2.1 (lib css)](http://twitter.github.io/bootstrap/)
  - [FontAwesome (lib proposant des icones en vectoriel)](http://fortawesome.github.io/Font-Awesome/)
  - [Less Css (facultatif. lib css : necessite d'installer le GEM Less)](http://lesscss.org/)
  - [Uniform (lib permettant de styliser les checkbox/select)](http://uniformjs.com/)
  - [Chosen (lib permettant de designer les select et select multiple)](http://harvesthq.github.io/chosen/)
  - [Bootstrap datepicker (un datepicker made in bootstrap)](http://www.eyecon.ro/bootstrap-datepicker/)


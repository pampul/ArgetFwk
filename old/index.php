<?php
session_start();
require 'AppConfig/autoload.php';
?>

<!DOCTYPE html>
<html>


    <head>
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <!–[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]–>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <base href="<?php echo $siteURL; ?>" />
        <title><?php
if ($baliseTitre[$content] != null) {

    echo $baliseTitre[$content];
} else {

    echo $baliseTitre["defaut"];
}
?>
        </title>
        <meta name="description" content="<?php
            if ($baliseDescription[$content] != null) {

                echo $baliseDescription[$content];
            } else {

                echo $baliseDescription["defaut"];
            }
?>
              " />
        <meta name="keywords" content="" />
        <meta name="google-site-verification" content="" />
        <meta name="author" content="AUTEUR" />

        <link rel="stylesheet" href="Web/css/style.css" type="text/css" />	

        <script type="text/javascript" src="http://apis.google.com/js/plusone.js">
            {lang: 'fr'}
        </script>

        <link rel="shortcut icon" href="/favicon.ico" />

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-30554453-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>

    </head>




    <body>

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=207851792615884";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <header id="header">
            <div id="header-wrapper">
                <?php
                require_once "Web/views/header.php";
                ?>
            </div>
        </header>


        <nav id="menu">
            <div id="menu-wrapper">
                <?php
                require_once "Web/views/menu.php";
                ?>
            </div>
        </nav>



        <section id="main">

            <div id="content-wrapper">

                <div id="content">

                    <?php
                    if ($content == null) {

                        require_once "Web/views/home.php";
                    } elseif ($content == 'home') {

                        require_once "Web/views/home.php";
                    } else {

                        require_once "Web/views/" . $content . ".php";
                    }
                    ?>

                </div>

            </div>

        </section>

        <footer id="footer">
            
            <div id="footer-content">
            <?php
            require_once "Web/views/footer.php";
            ?>
            </div>

        </footer>

    </body>


</html>
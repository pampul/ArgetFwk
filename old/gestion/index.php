<?php
session_start();
require 'AppConfig/autoload.php';
?>

<!DOCTYPE html>
<html>


    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" href="Web/css/style.css" type="text/css" />
        <link rel="stylesheet" href="Web/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css" />
        <link rel="stylesheet" href="Web/css/chosen.css" type="text/css" />
        <link rel="stylesheet" href="Tools/redactor/css/redactor.css" />

        <title>BO | Gestion de votre site internet</title>

        <script type="text/javascript" src="Web/js/jquery.js"></script>
        <script type="text/javascript" src="Web/js/jquery-ui.js"></script>
        <script type="text/javascript" src="Web/js/chosen-jquery.js"></script>
        <script type="text/javascript" src="Web/js/designcheckboxes.js"></script>
        <script type="text/javascript" src="Tools/redactor/redactor.js"></script>

        <!--<base href="" />-->
        <script type="text/javascript">
            $(
            function()
            {
                $('.redactor').redactor({ focus: false, imageUpload: 'Tools/redactor/module/upload.php', fileUpload: 'Tools/redactor/module/file_upload.php', lang: 'fr' });
                $('.redactormini').redactor({ focus: false, stoolbar: 'mini' });
            }
        );
        </script>

    </head>




    <body>

        <script type="text/javascript">
            (function(){
                $('.bordered tr').mouseover(function(){
                    $(this).addClass('highlight');
                }).mouseout(function(){
                    $(this).removeClass('highlight');
                });
            });
        
        </script>

        <?php if ($content != "login" && $content != "lostpassword" && $content != "changepassword") { ?>
            <div id="navigation">

                <?php
                require_once "navigation.php";
                ?>

            </div>
        <?php } ?>
        <?php if ($content != "login" && $content != "lostpassword" && $content != "changepassword") { ?>
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
            <?php
        } elseif ($content == "lostpassword") {

            require_once "Web/views/lostpassword.php";
        } elseif ($content == "changepassword") {

            require_once "Web/views/changepassword.php";
        } else {

            require_once "Web/views/login.php";
        }
        ?>

    </body>



</html>
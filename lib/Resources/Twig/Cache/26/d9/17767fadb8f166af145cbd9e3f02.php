<?php

/* index.html.twig */
class __TwigTemplate_26d917767fadb8f166af145cbd9e3f02 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'baseurl' => array($this, 'block_baseurl'),
            'title' => array($this, 'block_title'),
            'description' => array($this, 'block_description'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'header' => array($this, 'block_header'),
            'container' => array($this, 'block_container'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
            'jsinclude' => array($this, 'block_jsinclude'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
        <!–[if lt IE 9]>
        <!--<script src=\"//html5shiv.googlecode.com/svn/trunk/html5.js\"></script>-->
        <![endif]–>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

        <base href=\"";
        // line 11
        $this->displayBlock('baseurl', $context, $blocks);
        echo "\" />
        <title>";
        // line 12
        echo twig_escape_filter($this->env, constant("SITE_NOM"), "html", null, true);
        echo " | Gestion - ";
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <meta name=\"description\" content=\"";
        // line 13
        $this->displayBlock('description', $context, $blocks);
        echo "\" />

        <meta name=\"google-site-verification\" content=\"\" />
        <meta name=\"author\" content=\"";
        // line 16
        echo twig_escape_filter($this->env, constant("ADMIN_PRENOM"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, constant("ADMIN_PRENOM"), "html", null, true);
        echo "\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">


        <link rel=\"shortcut icon\" href=\"/favicon.ico\" />

        <link rel=\"stylesheet\" href=\"web/lib/bootstrap/css/bootstrap.min.css\" />
        <link rel=\"stylesheet\" href=\"web/lib/bootstrap/css/bootstrap-responsive.css\" />
        <link rel=\"stylesheet\" href=\"web/css/FwkStyle.css\" type=\"text/css\" />
        <link rel=\"stylesheet\" href=\"web/lib/css/uniform.default.css\" type=\"text/css\" />
        <link rel=\"stylesheet\" href=\"web/lib/redactor/css/redactor.css\" />
        <link rel=\"stylesheet\" href=\"web/css/style.css\" type=\"text/css\" />
        ";
        // line 28
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 31
        echo "
    </head>


    <body>

        ";
        // line 37
        $this->displayBlock('header', $context, $blocks);
        // line 42
        echo "
            <section id=\"main\" ";
        // line 43
        $this->displayBlock('container', $context, $blocks);
        echo ">
                    ";
        // line 44
        $this->displayBlock('content', $context, $blocks);
        // line 45
        echo "
                    ";
        // line 46
        $this->displayBlock('footer', $context, $blocks);
        // line 51
        echo "
                    </section>

                    <div id=\"confirmBox\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\" class=\"modal hide fade\">

                        <div class=\"modal-header\" id=confirmHeader>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                            <h3>Confirmation</h3>
                        </div>

                        <div class=\"modal-body\" id=\"confirmBody\">

                            Voulez-vous vraiment effectuer cette action ?

                        </div>

                        <div class=\"modal-footer\" id=\"confirmFooter\">
                            <a href=\"#\" class=\"btn\" id=\"confirmFalse\" data-dismiss=\"modal\" aria-hidden=\"true\">Annuler</a>
                            <a href=\"#\" class=\"btn btn-danger\" id=\"confirmTrue\" data-uri=\"\">Valider</a>
                        </div>

                    </div>

                    <div id=\"messageBox\" class=\"messageBox\">
                        <div class=\"contentBox\">
                            
                        </div>
                    </div>
        
                    <div id=\"editBox\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\" class=\"modal hide fade editBody\">
                        <div class=\"modal-header\" id=\"editHeader\">
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                            <h3>Ajout/Edition</h3>
                        </div>
                            
                        <div class=\"modal-body\" id=\"editBody\">

                        </div>
                            
                    </div>

                    <script src=\"web/lib/js/jquery.js\"></script>
                    <script src=\"web/lib/js/jquery-ui.js\"></script>
                    <script src=\"web/lib/bootstrap/js/bootstrap.min.js\"></script>
                    <script src=\"web/lib/js/uniformjs.js\"></script>
                    <script src=\"web/lib/js/AjaxLib.js\"></script>
                    <script src=\"web/lib/js/ArgetFwkLib.js\"></script>
                    <script src=\"web/lib/js/ArgetFwkUtilsLib.js\"></script>
                    <script src=\"web/lib/js/GoogleChartAPI.js\"></script>
                    <script src=\"web/lib/js/bootstrap-datepicker.js\"></script>
                    <script src=\"web/lib/redactor/redactor.min.js\"></script>
                    <script src=\"web/lib/js/jquery.form.js\"></script>
                    <script>
                        \$(function(){
                            \$().UItoTop({ easingType: 'easeOutQuart' });
                        });
                    </script>

            ";
        // line 109
        $this->displayBlock('jsinclude', $context, $blocks);
        // line 112
        echo "
                </body>

            </html>";
    }

    // line 11
    public function block_baseurl($context, array $blocks = array())
    {
    }

    // line 12
    public function block_title($context, array $blocks = array())
    {
        echo "En construction ...";
    }

    // line 13
    public function block_description($context, array $blocks = array())
    {
        echo " Description de le page ";
    }

    // line 28
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 29
        echo "        <!-- Ajout des css supplementaires here -->
        ";
    }

    // line 37
    public function block_header($context, array $blocks = array())
    {
        // line 38
        echo "        <header id=\"header\">
                    ";
        // line 39
        $this->env->loadTemplate("views/layouts/header.html.twig")->display($context);
        // line 40
        echo "            </header>
        ";
    }

    // line 43
    public function block_container($context, array $blocks = array())
    {
        echo "class=\"container\"";
    }

    // line 44
    public function block_content($context, array $blocks = array())
    {
    }

    // line 46
    public function block_footer($context, array $blocks = array())
    {
        // line 47
        echo "                    <footer id=\"footer\">
                        ";
        // line 48
        $this->env->loadTemplate("views/layouts/footer.html.twig")->display($context);
        // line 49
        echo "                        </footer>
                    ";
    }

    // line 109
    public function block_jsinclude($context, array $blocks = array())
    {
        // line 110
        echo "
            ";
    }

    public function getTemplateName()
    {
        return "index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  231 => 110,  228 => 109,  223 => 49,  221 => 48,  218 => 47,  215 => 46,  210 => 44,  204 => 43,  199 => 40,  197 => 39,  194 => 38,  191 => 37,  186 => 29,  183 => 28,  177 => 13,  171 => 12,  166 => 11,  159 => 112,  157 => 109,  97 => 51,  95 => 46,  92 => 45,  90 => 44,  86 => 43,  83 => 42,  81 => 37,  73 => 31,  71 => 28,  54 => 16,  48 => 13,  42 => 12,  26 => 1,  164 => 101,  155 => 94,  152 => 93,  135 => 75,  129 => 73,  125 => 71,  123 => 70,  104 => 53,  100 => 51,  96 => 49,  94 => 48,  66 => 22,  63 => 21,  57 => 18,  52 => 14,  49 => 13,  44 => 9,  38 => 11,  32 => 5,);
    }
}

<?php

/* views/layouts/header.html.twig */
class __TwigTemplate_e9a35f126973466c37078874fd3f9aed extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"navbar navbar-inverse navbar-fixed-top\">
    <div class=\"navbar-inner\">
        <div class=\"container\">
            <a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
            </a>
            <a class=\"brand\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, constant("SITE_URL"), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, constant("SITE_NOM"), "html", null, true);
        echo "</a>
            <div class=\"nav-collapse collapse\">
                <ul class=\"nav\">
                    <li class=\"active\"><a href=\"";
        // line 12
        echo twig_escape_filter($this->env, constant("SITE_URL"), "html", null, true);
        echo "\"><i class=\"icon-home\"></i> Accueil</a></li>
                </ul>

                <ul class=\"nav pull-right\">

                    <li class=\"dropdown\">

                        <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"><i class=\"icon-white icon-user\"></i> Admin  <b class=caret></b></a>
                        <ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"dLabel\">
                            <li><a href=\"dashboard/infos\">Mes informations</a></li>
                            <li><a href=\"dashboard/stats\">Statistiques</a></li>
                            <li><a href=\"dashboard/tickets\">Tickets / Erreurs</a></li>
                            ";
        // line 24
        if (isset($context["admin"])) { $_admin_ = $context["admin"]; } else { $_admin_ = null; }
        if (($this->getAttribute($this->getAttribute($_admin_, "privilege"), "level") > 8)) {
            // line 25
            echo "                                <li class=divider></li>
                                <li><a href=\"private/admins\">Gérer les administrateurs</a></li>
                                <li><a href=\"dashboard/privileges\">Gérer les privilèges</a></li>
                                <li><a href=\"dashboard/logs\">Consulter les logs</a></li>
                                ";
            // line 29
            if (isset($context["admin"])) { $_admin_ = $context["admin"]; } else { $_admin_ = null; }
            if (($this->getAttribute($this->getAttribute($_admin_, "privilege"), "level") > 9)) {
                // line 30
                echo "                                    <li><a href=\"test/test-page\">Crash Test Page</a></li>
                                    <li><a href=\"dashboard/config\">Configuration du site</a></li>
                                ";
            }
            // line 33
            echo "                            ";
        }
        // line 34
        echo "                                        <li class=divider></li>
                                        <li><a href=\"auth/logout\">Se déconnecter</a></li>
                                    </ul>

                                </li>

                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>";
    }

    public function getTemplateName()
    {
        return "views/layouts/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 34,  67 => 33,  62 => 30,  59 => 29,  53 => 25,  50 => 24,  35 => 12,  27 => 9,  17 => 1,  231 => 110,  228 => 109,  223 => 49,  221 => 48,  218 => 47,  215 => 46,  210 => 44,  204 => 43,  199 => 40,  197 => 39,  194 => 38,  191 => 37,  186 => 29,  183 => 28,  177 => 13,  171 => 12,  166 => 11,  159 => 112,  157 => 109,  97 => 51,  95 => 46,  92 => 45,  90 => 44,  86 => 43,  83 => 42,  81 => 37,  73 => 31,  71 => 28,  54 => 16,  48 => 13,  42 => 12,  26 => 1,  164 => 101,  155 => 94,  152 => 93,  135 => 75,  129 => 73,  125 => 71,  123 => 70,  104 => 53,  100 => 51,  96 => 49,  94 => 48,  66 => 22,  63 => 21,  57 => 18,  52 => 14,  49 => 13,  44 => 9,  38 => 11,  32 => 5,);
    }
}

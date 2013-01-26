<?php

/* views/layouts/footer.html.twig */
class __TwigTemplate_a81c135d52ffb69d9c339a0f2750499d extends Twig_Template
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
        echo "<hr>

";
        // line 3
        if (array_key_exists("tempsChargement", $context)) {
            echo "<small class=\"pull-right\">Chargement de la page : ";
            if (isset($context["tempsChargement"])) { $_tempsChargement_ = $context["tempsChargement"]; } else { $_tempsChargement_ = null; }
            echo twig_escape_filter($this->env, $_tempsChargement_, "html", null, true);
            echo " secondes</small>";
        }
        // line 4
        echo "
<small>";
        // line 5
        echo twig_escape_filter($this->env, constant("SITE_NOM"), "html", null, true);
        echo " - Copyright 2012 - All Rights Reserved</small>";
    }

    public function getTemplateName()
    {
        return "views/layouts/footer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 5,  28 => 4,  21 => 3,  70 => 34,  67 => 33,  62 => 30,  59 => 29,  53 => 25,  50 => 24,  35 => 12,  27 => 9,  17 => 1,  231 => 110,  228 => 109,  223 => 49,  221 => 48,  218 => 47,  215 => 46,  210 => 44,  204 => 43,  199 => 40,  197 => 39,  194 => 38,  191 => 37,  186 => 29,  183 => 28,  177 => 13,  171 => 12,  166 => 11,  159 => 112,  157 => 109,  97 => 51,  95 => 46,  92 => 45,  90 => 44,  86 => 43,  83 => 42,  81 => 37,  73 => 31,  71 => 28,  54 => 16,  48 => 13,  42 => 12,  26 => 1,  164 => 101,  155 => 94,  152 => 93,  135 => 75,  129 => 73,  125 => 71,  123 => 70,  104 => 53,  100 => 51,  96 => 49,  94 => 48,  66 => 22,  63 => 21,  57 => 18,  52 => 14,  49 => 13,  44 => 9,  38 => 11,  32 => 5,);
    }
}

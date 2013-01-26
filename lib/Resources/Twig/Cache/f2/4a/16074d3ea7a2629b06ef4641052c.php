<?php

/* views/config.html.twig */
class __TwigTemplate_f24a16074d3ea7a2629b06ef4641052c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("index.html.twig");

        $this->blocks = array(
            'baseurl' => array($this, 'block_baseurl'),
            'title' => array($this, 'block_title'),
            'description' => array($this, 'block_description'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'container' => array($this, 'block_container'),
            'content' => array($this, 'block_content'),
            'jsinclude' => array($this, 'block_jsinclude'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_baseurl($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, constant("SITE_URL"), "html", null, true);
    }

    // line 8
    public function block_title($context, array $blocks = array())
    {
        echo "Configuration du site";
    }

    // line 9
    public function block_description($context, array $blocks = array())
    {
    }

    // line 13
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 14
        echo "
";
    }

    // line 18
    public function block_container($context, array $blocks = array())
    {
        echo "class=\"container\"";
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
        // line 22
        echo "
<div class=\"row\">

    <br/><br/><br/>


    <div class=\"span12\">

        <div class=\"widget-content\">

            <h4 class=\"fwk-title\">Configuration du site</h4>
            <hr/>

            <br/>
            
            <div class=\"row\">
            
                <div class=\"span5 alert\">
                
                    Le site est en mode intégration seule en Front Office (sans utiliser de PHP) :
                
                </div>
                
                <div class=\"span2 alert\">
                
                    <strong>
                    ";
        // line 48
        if (constant("CONFIG_DEV_PHP")) {
            // line 49
            echo "                        Oui
                    ";
        } else {
            // line 51
            echo "                        Non
                    ";
        }
        // line 53
        echo "                    </strong>
                
                </div>
            
            </div>
            
            <div class=\"row\">
            
                <div class=\"span5 alert\">
                
                    Le site utilise le cache de Twig :
                
                </div>
                
                <div class=\"span2 alert\">
                
                    <strong>
                    ";
        // line 70
        if ((constant("TWIG_CACHE_PATH") == false)) {
            // line 71
            echo "                        Non
                    ";
        } else {
            // line 73
            echo "                        A ce chemin : \"";
            echo twig_escape_filter($this->env, constant("TWIG_CACHE_PATH"), "html", null, true);
            echo "\"
                    ";
        }
        // line 75
        echo "                    </strong>
                
                </div>
            
            </div>

        </div>

    </div>

</div>


";
    }

    // line 93
    public function block_jsinclude($context, array $blocks = array())
    {
        // line 94
        echo "<script>
                        
    \$('#modifierAvatar').click(function(){
        \$('#modifierAvatarModal').modal({
                                
        });
                        
        \$('#inputsVals').html('<input type=\"file\" name=\"logotoupload\" class=\"ajaxImageUpload\" data-max-size=\"1000000\" data-formats=\"png,jpg,jpeg\" data-filename=\"logotoupload\" data-path=\"avatars\" data-perso=\"";
        // line 101
        if (isset($context["objUser"])) { $_objUser_ = $context["objUser"]; } else { $_objUser_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_objUser_, "id"), "html", null, true);
        echo "\"><input type=\"hidden\" name=\"logo\" /><div class=\"loadingDiv\"></div>');
        \$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
    });
    </script>
";
    }

    public function getTemplateName()
    {
        return "views/config.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  164 => 101,  155 => 94,  152 => 93,  135 => 75,  129 => 73,  125 => 71,  123 => 70,  104 => 53,  100 => 51,  96 => 49,  94 => 48,  66 => 22,  63 => 21,  57 => 18,  52 => 14,  49 => 13,  44 => 9,  38 => 8,  32 => 5,);
    }
}

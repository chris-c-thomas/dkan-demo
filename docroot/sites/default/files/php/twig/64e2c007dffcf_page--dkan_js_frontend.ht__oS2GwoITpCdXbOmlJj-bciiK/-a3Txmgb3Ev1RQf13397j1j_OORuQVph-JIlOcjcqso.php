<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/contrib/dkan/modules/dkan_js_frontend/templates/page--dkan_js_frontend.html.twig */
class __TwigTemplate_e2e5bc5638bc2124fb08ea1247b28966b244055826b2954d80ac13b3f656ced1 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 52
        echo "<div id=\"root\">
 
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/dkan/modules/dkan_js_frontend/templates/page--dkan_js_frontend.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 52,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/dkan/modules/dkan_js_frontend/templates/page--dkan_js_frontend.html.twig", "/var/www/html/docroot/modules/contrib/dkan/modules/dkan_js_frontend/templates/page--dkan_js_frontend.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}

<?php

namespace WPML\Core;

use \WPML\Core\Twig\Environment;
use \WPML\Core\Twig\Error\LoaderError;
use \WPML\Core\Twig\Error\RuntimeError;
use \WPML\Core\Twig\Markup;
use \WPML\Core\Twig\Sandbox\SecurityError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedTagError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFilterError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFunctionError;
use \WPML\Core\Twig\Source;
use \WPML\Core\Twig\Template;

/* 
<div class="{{ css_classes }} lang-desctop-wrap">

   {% for code, language in languages %}
           <a href="{{ language.url }}" data-lang="{{language.code}}" class="{{ language.css_classes }}">
           {{ language.display_name  }}
           </a>

   {% endfor %}

</div>
 */
class __TwigTemplate_5c9cb6e8e6423a39ce960c1232c937b98c01fb324b968f5f410d31d42344d9f8 extends \WPML\Core\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "
<div class=\"";
        // line 2
        echo \WPML\Core\twig_escape_filter($this->env, ($context["css_classes"] ?? null), "html", null, true);
        echo " lang-desctop-wrap\">

   ";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["languages"] ?? null));
        foreach ($context['_seq'] as $context["code"] => $context["language"]) {
            // line 5
            echo "           <a href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["language"], "url", []), "html", null, true);
            echo "\" data-lang=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["language"], "code", []), "html", null, true);
            echo "\" class=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["language"], "css_classes", []), "html", null, true);
            echo "\">
           ";
            // line 6
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["language"], "display_name", []), "html", null, true);
            echo "
           </a>

   ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['code'], $context['language'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "
<div class=\"{{ css_classes }} lang-desctop-wrap\">

   {% for code, language in languages %}
           <a href=\"{{ language.url }}\" data-lang=\"{{language.code}}\" class=\"{{ language.css_classes }}\">
           {{ language.display_name  }}
           </a>

   {% endfor %}

</div>
";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 10,  64 => 6,  55 => 5,  51 => 4,  46 => 2,  43 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("
<div class=\"{{ css_classes }} lang-desctop-wrap\">

   {% for code, language in languages %}
           <a href=\"{{ language.url }}\" data-lang=\"{{language.code}}\" class=\"{{ language.css_classes }}\">
           {{ language.display_name  }}
           </a>

   {% endfor %}

</div>
", "
<div class=\"{{ css_classes }} lang-desctop-wrap\">

   {% for code, language in languages %}
           <a href=\"{{ language.url }}\" data-lang=\"{{language.code}}\" class=\"{{ language.css_classes }}\">
           {{ language.display_name  }}
           </a>

   {% endfor %}

</div>
", "");
    }
}

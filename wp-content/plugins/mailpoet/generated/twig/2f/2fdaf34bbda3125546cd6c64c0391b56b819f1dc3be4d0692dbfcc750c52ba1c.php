<?php

if (!defined('ABSPATH')) exit;


use MailPoetVendor\Twig\Environment;
use MailPoetVendor\Twig\Error\LoaderError;
use MailPoetVendor\Twig\Error\RuntimeError;
use MailPoetVendor\Twig\Extension\SandboxExtension;
use MailPoetVendor\Twig\Markup;
use MailPoetVendor\Twig\Sandbox\SecurityError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedTagError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFilterError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
use MailPoetVendor\Twig\Source;
use MailPoetVendor\Twig\Template;

/* automation/editor.html */
class __TwigTemplate_fb71bf89a426c5b5b4c882632d479b3cffbaded39146d864b8aea902ce949403 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'container' => [$this, 'block_container'],
            'after_css' => [$this, 'block_after_css'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout.html", "automation/editor.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_container($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "<div id=\"mailpoet_automation_editor\" class=\"edit-site\"></div>

<script type=\"text/javascript\">
  var mailpoet_automation_api = ";
        // line 7
        echo json_encode(($context["api"] ?? null));
        echo ";
  var mailpoet_json_api = ";
        // line 8
        echo json_encode(($context["jsonapi"] ?? null));
        echo ";
  var mailpoet_user_roles = ";
        // line 9
        echo json_encode(($context["user_roles"] ?? null));
        echo ";
  var mailpoet_segments = ";
        // line 10
        echo json_encode(($context["segments"] ?? null));
        echo ";
  var mailpoet_automation_context = ";
        // line 11
        echo json_encode(($context["context"] ?? null));
        echo ";
  var mailpoet_automation = ";
        // line 12
        echo ((($context["automation"] ?? null)) ? (json_encode(($context["automation"] ?? null))) : ("undefined"));
        echo ";
</script>
";
    }

    // line 16
    public function block_after_css($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 17
        echo $this->extensions['MailPoet\Twig\Assets']->generateStylesheet("mailpoet-automation-editor.css");
        echo "
";
    }

    public function getTemplateName()
    {
        return "automation/editor.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 17,  83 => 16,  76 => 12,  72 => 11,  68 => 10,  64 => 9,  60 => 8,  56 => 7,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "automation/editor.html", "/home/circleci/mailpoet/mailpoet/views/automation/editor.html");
    }
}

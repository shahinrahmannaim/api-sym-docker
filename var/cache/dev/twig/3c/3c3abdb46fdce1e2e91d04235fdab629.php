<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* admin/dashboard/dashboard.html.twig */
class __TwigTemplate_f56255df2eb51c5420eaf2f64a8b369d extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/dashboard/dashboard.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/dashboard/dashboard.html.twig"));

        // line 2
        yield "
";
        // line 4
        yield "
";
        // line 6
        yield "    ";
        // line 7
        yield "
     ";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "admin/dashboard/dashboard.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  56 => 7,  54 => 6,  51 => 4,  48 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# {% extends 'admin/admin.html.twig' %} #}

{# {% block title %}Dashboard{% endblock %} #}

{# {% block body %} #}
    {# <h1>Welcome to the Admin Dashboard</h1> #}

     {# {% if section == 'users' %}
        {% include 'admin/user/userlist.html.twig' %}
    {% elseif section == 'recipe' %}
        {% include 'admin/recipe/index.html.twig' %}
    {% elseif section == 'category' %}
        {% include 'admin/category/index.html.twig' %}
    {% elseif section == 'department' %}
        {% include 'department/department.html.twig' %}
    {% else %}
        <p>No section selected.</p> 
    {% endif %}  #}
{# {% endblock %} #}
", "admin/dashboard/dashboard.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/sym-angu-rect-docker/symfony-7/templates/admin/dashboard/dashboard.html.twig");
    }
}

<?php
namespace Braincase\Slim;

trait TemplateEngineTrait
{
	protected $container; // DIC
	protected $templatePath;
	protected $template = null;

	protected function initTemplateEngine($container, $templatePath)
	{
		$this->container = $container;
		$this->templatePath = $templatePath;
	}

	public function withTemplate($templateFile, $data)
	{
		$clone = clone $this;

		$clone->template = new Template($templateFile, $data, $this->templatePath, $this->container->session);

		return $clone;
	}

	public function getTemplate()
	{
		return $this->template;
	}

}

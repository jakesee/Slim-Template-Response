<?php
namespace Braincase\Slim;

class Template implements TemplateInterface
{
	protected $container;
	protected $templatePath;
	protected $templateFile;
	public $data;

	public function __construct($container, string $templatePath)
	{
		$this->container = $container;
		$this->templatePath = $templatePath;
	}

	public function __get($propertyName)
	{
		if(isset($this->container[$propertyName]))
			return $this->container[$propertyName];
	}

	public function prepare(string $templateFile, array $data)
	{
		$this->templateFile = $templateFile;
		$this->data = $data;

		return $this;
	}

	protected function extend($templateFile)
	{
		echo $this->buffer($templateFile);
	}

	public function toString() : string
	{
		return $this->buffer($this->templateFile);
	}

	public function src($url)
	{
		return $this->request->getUri()->getBaseUrl() . '/' . $url;
	}

	private function buffer($templateFile)
	{
		$level = ob_get_level();

		ob_start();

		include($this->getTemplateFile($templateFile));

		return ob_get_clean();
	}

	private function getTemplateFile($templateFile)
	{
		return $this->templatePath . $templateFile;
	}

	protected function viewData()
	{
		echo '<pre>';
		print_r($this->data);
		echo '</pre>';
	}
}
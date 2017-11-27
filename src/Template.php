<?php
namespace Braincase\Slim;

class Template
{
	private $templatePath;
	private $templateFile;
	protected $session;
	public $data;

	public function __construct($templateFile, $data, $templatePath, $session)
	{
		$this->data = [
			'result' => 0,
			'errors' => [],
		];

		$this->templateFile = $templateFile;
		$this->data = array_merge($this->data, $data);
		$this->templatePath = $templatePath;
		$this->session = $session;
	}

	public function toString()
	{
		return $this->buffer($this->templateFile);
	}

	public function src($url)
	{
		return $this->request->getUri()->getBaseUrl() . '/' . $url;
	}

	protected function extend($templateFile)
	{
		echo $this->buffer($this->getTemplateFile($templateFile));
	}

	protected function viewData()
	{
		echo '<pre>';
		print_r($this->data);
		echo '</pre>';
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
}

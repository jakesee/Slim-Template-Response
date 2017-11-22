<?php
namespace Braincase\Slim;

interface TemplateInterface
{
	/**
	* Compile the template and return as a string
	* 
	* @return string
	*/
	public function toString();

	/**
	* Prepare the template with the template data to be rendered
	* 
	* @return void
	*/
	public function prepare($templateFile, array $data);
}
<?php
   /**
	* Copyright (c) 2003 Brian E. Lozier (brian@massassi.net)
	*
	* set_vars() method contributed by Ricardo Garcia (Thanks!)
	*
	* Permission is hereby granted, free of charge, to any person obtaining a copy
	* of this software and associated documentation files (the "Software"), to
	* deal in the Software without restriction, including without limitation the
	* rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
	* sell copies of the Software, and to permit persons to whom the Software is
	* furnished to do so, subject to the following conditions:
	*
	* The above copyright notice and this permission notice shall be included in
	* all copies or substantial portions of the Software.
	*
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
	* IN THE SOFTWARE.
    */

class Template {

	var $vars;
	var $path = "templates/";
	var $file;							 	// File of the template
	//var $paths = 'templates';
	var $success;
	var $outputBuffer;
	var $globalVars = array();

	/*
	 * Constructor
	 *
	 * @param string $path the path to the templates
	 *
	 * @return void
	 */

	function Template($file, $path = NULL)
	{
		$this->setFile($file);
		$this->setPath($path);
		$this->outputBuffer = NULL;
		//$this->paths = preg_split("/[:;]/", ini_get('include_path'));
		$this->vars = array();
	}

	/*
	 * Set the path to the template files.
	 *
	 * @param string $path path to template files
	 *
	 * @return void
	 */

	function setPath($path)
	{
		if (strlen($path) > 0 && substr($path, strlen($path), 1) != '/') $path .= '/';
		//if (substr($path, 0, 1) == '/') $path = substr($path, 1);
		if($path != NULL) $this->path = $path;
    	}

	/*
	 * Set the name of the template file.
	 *
	 * @param string $file - file of the template
	 *
	 * @return void
	 */

	function setFile($file, $path = NULL)
	{
		if ($path != NULL) $this->setPath($path);
		$this->file = $file;
	}

	/*
	 * Set a template variable.
	 *
	 * @param string $name name of the variable to set
	 * @param mixed $value the value of the variable
	 *
	 * @return void
	 */

	function set($name, $value)
	{
		if ($this) $this->vars[$name] = $value;
		//Template::$globalVars[$name] = $value;
	}

	/*
	 * Set a template variable.
	 *
	 * @param string $name name of the variable to set
	 * @param mixed $value the value of the variable
	 *
	 * @return void
	 */

	function get($name)
	{
		if ($this && array_key_exists($name, $this->vars))
		{
			return $this->vars[$name];
		}
		//else if (array_key_exists($name, Template::globalVars))
		//{
		//	return Template::globalVars[$name];
		//}
		else
		{
			return false;
		}
	}

	/*
	 * Set a bunch of variables at once using an associative array.
	 *
	 * @param array $vars array of vars to set
	 * @param bool $clear whether to completely overwrite the existing vars
	 *
	 * @return void
	 */

	function getVars()	// Use only for debugging.
	{
		$vars = array();
		//$vars = array_merge($vars, Template::$globalVars);
		if ($this) $vars = array_merge($vars, $this->vars);

		echo "<pre>";
		echo "Var Count: " . count($this->vars) . "\n";
		print_r($vars);
		echo "</pre>";
		exit;
	}

	function setVars($vars, $clear = false)
	{
		if($clear && $this)
		{
           $this->vars = $vars;
		}
		elseif(is_array($vars))
		{
			if ($this) $this->vars = array_merge($this->vars, $vars);
			//Template::$globalVars = array_merge(Template::$globalVars, $vars);
		}
	}

	function callbackBuffer($buffer)
	{
		return $buffer;
	}

	function startBuffer($name)
	{
		$this->outputBuffer = $name;
		ob_start("Template::callbackBuffer");
		$this->vars[$name] = $value;
	}

	function stopBuffer()
	{
		if ($this->outputBuffer !== NULL)
			$this->vars[$this->outputBuffer] = ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param string string the template file name
	 *
	 * @return string
	 */

	function fetch()
	{
		$full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->path . $this->file . '.tpl.php';
		
		
    	if (file_exists($full_path)) {
			//extract(Template::$globalVars);
			extract($this->vars);													// Extract the vars to local namespace

			ob_start();																// Start output buffering
			$this->success = include($full_path);  		// Include the file
			$contents = ob_get_contents();											// Get the contents of the buffer
			ob_end_clean();
        													// End buffering and discard

			//Did we succeed?
			$this->success = ($this->success !== false && $contents != '');

			if ($this->success) return $contents;														// Return the contents
			else return "<!-- ERROR: Template returned an error -->";
		}
		else return '<!-- ERROR: Could not locate template file "' . $this->file . '.tpl.php"-->';
	}
}

/**
 * An extension to Template that provides automatic caching of
 * template contents.
 * @package
 * @author Joshua Duck
 * @todo This class must be in a separate file.
 */

?>
<?php

class FlynsarmySASS_Module extends Core_ModuleBase
{

     /**
     * Creates the module information object
     * @return Core_ModuleInfo
     */
    protected function createModuleInfo()
    {
      return new Core_ModuleInfo(
        "SASS Compiler",
        "Automatically compiles SASS files",
        "Flynsarmy" );
    }

	public function subscribeEvents()
	{
		Backend::$events->addEvent('cms:onBeforeResourceCombine', $this, 'before_resource_combine');
	}

	public function before_resource_combine( $array )
	{
		//We're only dealing with CSS resources
		if ( $array['type'] != 'css' )
			return;

		foreach( $array['files'] as $file )
		{
			//Only deal with CSS files
			if ( strpos($file, '.css') === false )
				continue;

			if ( strpos($file, '@') !== false )
				$file = theme_resource_url(str_replace('@', '', $file));

			//Get SASS and CSS filepaths
			$css = PATH_APP . $file;
			$sass = substr($css, 0, strlen($css)-4).'.sass';
			$extension = 'sass';
			if ( !file_exists($sass) )
			{
				$sass = substr($css, 0, strlen($css)-4).'.scss';
				$extension = 'scss';
			}

			if ( !file_exists($sass) )
				continue;

			self::compile_sass($sass, $extension, $css);
		}
	}

	public static function compile_sass( $sass_fname, $syntax, $css_fname )
	{
		require_once(dirname(__FILE__).'/phpsass/SassParser.php');
		
		//Get creation date of both. See if the SASS file has been updated
		$css_ftime = file_exists($css_fname) ? filemtime($css_fname) : 0;
		$sass_ftime = filemtime($sass_fname);

		if ( $sass_ftime <= $css_ftime )
			return false;

		// Execute the compiler.
		$parser = new SassParser(array(
			'style' => 'expanded',
			'syntax' => $syntax,
			'filename' => $sass_fname,
			//'debug' => FALSE,
			//'callbacks' => array(
			//	'warn' => 'cb_warn',
			//	'debug' => 'cb_debug',
			//),
		));
		file_put_contents($css_fname, $parser->toCss($sass_fname));
		return true;
	}
}
?>
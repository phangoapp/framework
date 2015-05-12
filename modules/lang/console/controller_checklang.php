<?php

function checklangConsole()
{

	$arr_options=get_opts_console('', $arr_opts=array('module::', 'all', 'status'));
	
	if(!isset($arr_options['module']) && !isset($arr_options['all']))
	{
	
		echo "Use: php console.php -m=padmin -c=padmin [--all] [--module=module] [--status]\n\n";
		echo "If you use --module and --all together, the script use the --module option always\n";
		die;
	
	}

	echo "This script create language files...\n";
	echo "Scanning files and directories...\n";

	$i18n_dir='./i18n/';

	//Creating language folders if exists...
	/*var_dump($arr_options);
	die;*/
	if(isset($arr_options['status']))
	{

		echo "Checking status...\n";
		scan_directory_status(PhangoVar::$base_path);
		
	}
	else if(@$arr_options['module']!='')
	{

		//scan_file($argv[1]);

		if(file_exists($arr_options['module']))
		{
		
			if(is_dir($arr_options['module']))
			{

				echo "Scanning ".$arr_options['module']."...\n";

				scan_directory($arr_options['module']);
				
				//check_i18n_file($arr_options['module']);
				
			}
			else
			{
			
				echo $arr_options['module']." is not a directory...\n";
			
			}

		}
		else
		{

			echo $arr_options['module']." file don't exists...\n";

		}

	}
	else
	{

		scan_directory(PhangoVar::$base_path.'/');

	}
	
}

function scan_directory($directory)
{

	foreach(I18n::$arr_i18n as $language) 
	{

		if(!file_exists(PhangoVar::$base_path.'/i18n/'.$language)) 
		{

			mkdir(PhangoVar::$base_path.'/i18n/'.$language);

		}

	}
	
	if( false !== ($handledir=opendir($directory)) ) 
	{

		while (false !== ($file = readdir($handledir))) 
		{
			
			$path_file=$directory.$file;
			
			if( !preg_match ( '/(.*)\/i18n\/(.*)/' , $path_file ) )
			{    
				if(is_dir($path_file) && !preg_match("/^(.*)\.$/", $path_file) && !preg_match("/^\.(.*)$/", $path_file)) 
				{
					
					echo "Checking directory ".$path_file.'/'.$file."...\n";
					scan_directory($path_file.'/');
					
				}
				else
				if(preg_match("/.*\.php$/", $file) && $file!="controller_checklang.php" ) 
				{
	
					echo "Checking file $path_file...\n";

					//Check file...

					//First open file...
					
					check_i18n_file($directory.$file);

				}

			}
			else
			{

				echo "No checking i18n file $file...\n"; 

			}
				

		}
		
		closedir($handledir);

	}

}

function check_i18n_file($file_path)
{

	//Check file searching $lang variables...
	
	$file=file_get_contents($file_path);

	//Get $lang variables......
	$arr_match_lang=array();
	
	//I18n::lang(\1, \2, \3)
	
	$pattern_file="|I18n::lang\('(.*)',\s+'(.*)',\s+'(.*)'\)?|U";
		
	if(preg_match_all ( $pattern_file, $file, $arr_match_lang, PREG_SET_ORDER)) 
	{

	//Check if exists lang file for $lang variable

		I18n::$lang=array();

		foreach($arr_match_lang as $arr_lang) 
		{
	
			if(!isset(I18n::$lang[$arr_lang[1]])) 
			{
	
				I18n::$lang[$arr_lang[1]]=array();
			
			}
	
			I18n::$lang[$arr_lang[1]][$arr_lang[2]]=$arr_lang[3];
		
		}
			
		foreach(I18n::$arr_i18n as $language) 
		{
	
			$arr_files=array_unique(array_keys(I18n::$lang));
				
			foreach($arr_files as $lang_file)
			{

				$path_lang_file=PhangoVar::$base_path.'/i18n/'.$language.'/'.$lang_file.'.php';

				$module_path=$lang_file;
				
				$pos=strpos($module_path, "_");
				//echo $module_path."\n";
				
				$yes_check_file=1;
				
				if($pos!==false)
				{
				
					$arr_path=explode('_', $module_path);

					$module_path=$arr_path[0];
					
					if($arr_path[1]=='admin')
					{
					
						$yes_check_file=0;
					
					}
					
				}
				
				if($yes_check_file==1)
				{

					if(file_exists(PhangoVar::$base_path.'/modules/'.$module_path))
					{

						/*foreach(I18n::$arr_i18n as $lang_dir) 
						{*/

						if(!file_exists(PhangoVar::$base_path.'/modules/'.$module_path.'/i18n/'.$language)) 
						{
							//echo PhangoVar::$base_path.'/modules/'.$lang_file.'/i18n/'.$language;
							mkdir(PhangoVar::$base_path.'/modules/'.$module_path.'/i18n/'.$language, 0755, true);

						}

						//}

						$path_lang_file=PhangoVar::$base_path.'/modules/'.$module_path.'/i18n/'.$language.'/'.$lang_file.'.php';

					}
					
					include($path_lang_file);
						
					//print_r($lang);
						
					$arr_file_lang=array("<?php\n\n");

					foreach(I18n::$lang[$lang_file] as $key_trad => $trad) 
					{
						
						$arr_file_lang[]="I18n::\$lang['".$lang_file."']['".$key_trad."']='".addslashes($trad)."';\n\n";
						
					}
						
					/*foreach($lang as $file_lang => $value_lang) 
					{
						
						foreach($value_lang as $key_trad => $trad) 
						{
						
							$arr_file_lang[]="\$lang['".$file_lang."']['".$key_trad."']='".$trad."';\n\n";
							
						}
						
					}*/
					
					$arr_file_lang[]="?>\n";
					
					$file=fopen ($path_lang_file, 'w');
					
					if($file!==false) 
					{
					
						echo "--->Writing in this file: ".$path_lang_file."...\n";
					
						if(fwrite($file, implode('', $arr_file_lang))==false) 
						{
						
							echo "I cannot open this file: $path_lang_file\n";
							die;
						
						}
					
						fclose($file);
					
					}
					else
					{
					
						echo "I cannot open this file: $path_lang_file\n";
						die;
					
					}
				}
			}
		
			
		}
		
	}

}

function scan_directory_status($directory)
{

	foreach(I18n::$arr_i18n as $language) 
	{

		if(!file_exists(PhangoVar::$base_path.'/i18n/'.$language)) 
		{

			mkdir(PhangoVar::$base_path.'/i18n/'.$language);

		}

	}
	if( false !== ($handledir=opendir($directory)) ) 
	{

		while (false !== ($file = readdir($handledir))) 
		{
			
			$path_file=$directory.$file;

			//echo $path_file."\n";

			if(!preg_match("/^(.*)\.$/", $path_file) && !preg_match("/^\.(.*)$/", $path_file))
			{

				if(is_dir($path_file)) 
				{
						
					//echo "Checking directory ".$file."...\n";
					scan_directory_status($path_file.'/');

				}
				else if( preg_match ( '/(.*)\/i18n\/(.*)\.php$/' , $path_file ) )
				{
					I18n::$lang=array();
					echo "Checking file ".$path_file."...\n";

					include($path_file);

					//$file_lang=str_replace('.php', '', $file);

					$file_lang=key(I18n::$lang);
	
					foreach(I18n::$lang[$file_lang] as $key_lang => $cont_lang)
					{
						
						if($key_lang==$cont_lang)
						{

							echo "--- I18n::\$lang[".$file_lang."][".$key_lang."] need translation\n";

						}

					}

					echo "\n\n";

					//print_r($lang);
					

				}

			}
					

		}

	}

}

?>

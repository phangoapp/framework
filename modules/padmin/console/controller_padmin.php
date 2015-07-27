<?php

use PhangoApp\PhaModels\SQLClass;

function padminConsole()
{

	$options=get_opts_console('', $arr_opts=array('model:'));
	
	$climate = new League\CLImate\CLImate;
	
	if(!isset($options['model']))
	{
	
		//echo "Use: php console.php -m=padmin -c=padmin --model=module/model\n";
		
		$climate->white()->backgroundBlack()->out("Use: php console.php -m=padmin -c=padmin --model=module/model");
		
		die;
	
	}
	
	$arr_option=explode('/', $options['model']);

	settype($arr_option[0], 'string');
	settype($arr_option[1], 'string');
	
	Webmodel::$model_path=getcwd().'/modules/';
	
	$model_file=Webmodel::$model_path.$arr_option[0].'/models/models_'.$arr_option[1].'.php';
	
	if(!is_file($model_file))
	{

		$climate->white()->backgroundRed()->out("Error: cannot find the model file in ".$arr_option[0]."/models/models_".$arr_option[1].".php");
	
		die();

	}


	WebModel::load_model($options['model']);

	try {

		$first_item=current(Webmodel::$model);
		
		$first_item->connect_to_db();
		
	} catch(Exception $e)
	{
		$climate->white()->backgroundRed()->out($e->getMessage());
		//echo $e->getMessage()."\n";
		die;

	}

	//print_r(get_declared_classes());



	update_table();

	$post_install_script=Webmodel::$model_path.$arr_option[0].'/install/post_install.php';

	$post_install_lock=Webmodel::$model_path.$arr_option[0].'/install/lock';

	if(file_exists($post_install_script) && !file_exists($post_install_lock))
	{

		//echo "Executing post_install script...\n";
		
		$climate->white()->backgroundBlack()->out('Executing post_install script...');

		include($post_install_script);

		if(post_install())
		{
		
			if(!file_put_contents($post_install_lock, 'installed'))
			{
			
				//echo "Done, but cannot create this file: ".$arr_option[0].'/install/lock'.". Check your permissions and create the file if the script executed satisfally \n";
				$climate->white()->backgroundBlack()->out("Done, but cannot create this file: ".$arr_option[0].'/install/lock'.". Check your permissions and create the file if the script executed satisfally");
			
			}
			else
			{
			
				//echo "Done\n";
				$climate->white()->backgroundBlack()->out('Done');
			
			}
		
		}
		else
		{
		
			//echo "Error, please, check ${post_install_script} file and execute padmin.php again\n";
			$climate->white()->backgroundRed()->out("Error, please, check ${post_install_script} file and execute padmin.php again");
		
		}

	}

	//echo "All things done\n";
	
	$climate->white()->backgroundBlack()->out("All things done");

}

/**
* This Function is used for padmin.php for create new tables and fields based in Webmodel class.
*
* @param Webmodel $model The model used for create or update a sql table.
*/

function update_table()
{
	//include(__DIR__.'/../src/Databases/'.Webmodel::$type_db.'.php');
	
	Webmodel::$arr_sql_index=array();
	Webmodel::$arr_sql_set_index=array();
	
	Webmodel::$arr_sql_unique=array();
	Webmodel::$arr_sql_set_unique=array();
	
	$arr_etable=array();
	
	$query=SQLClass::webtsys_query("show tables");
		
	while(list($table)=SQLClass::webtsys_fetch_row($query))
	{
	
		$arr_etable[$table]=1;
	
	}
	
	foreach(Webmodel::$model as $key => $thing)
	{
		
		$arr_table=array();
		
		$allfields=array();
		$fields=array();
		$types=array();
	
		$field="";
		$type=""; 
		$null=""; 
		$key_db=""; 
		$default=""; 
		$extra="";
		$key_field_old=Webmodel::$model[$key]->idmodel;
		
		if(!isset($arr_etable[$key]))
		{
			//If table not exists make this
			
			echo "Creating table $key\n";
			
			Webmodel::$model[$key]->create_table();
			
			/*foreach(Webmodel::$model[$key]->components as $field => $data)
			{
			
				$arr_table[]='`'.$field.'` '.Webmodel::$model[$key]->components[$field]->get_type_sql();

				//Check if indexed
				
				if(Webmodel::$model[$key]->components[$field]->indexed==true)
				{
				
					Webmodel::$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON '.$key.'(`'.$field.'`);';
					Webmodel::$arr_sql_set_index[$key][$field]='';
				
				}
				
				//Check if unique
				
				if(Webmodel::$model[$key]->components[$field]->unique==true)
				{
				
					Webmodel::$arr_sql_unique[$key][$field]=' ALTER TABLE `'.$key.'` ADD UNIQUE (`'.$field.'`)';
					Webmodel::$arr_sql_set_unique[$key][$field]='';
				
				}
				
				//Check if foreignkeyfield...
				if(isset(Webmodel::$model[$key]->components[$field]->related_model))
				{

					//Create indexes...
					
					Webmodel::$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON '.$key.'(`'.$field.'`);';
					
					$table_related=Webmodel::$model[$key]->components[$field]->related_model->name;
					
					$id_table_related=load_id_model_related(Webmodel::$model[$key]->components[$field], $model);

					//'Id'.ucfirst(Webmodel::$model[$key]->components[$field]->related_model);				
					
					Webmodel::$arr_sql_set_index[$key][$field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$field.'_'.$key.'IDX` FOREIGN KEY ( `'.$field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';

				}
			}*/
			
			/*$sql_query="create table `$key` (\n".implode(",\n", $arr_table)."\n) DEFAULT CHARSET=utf8;\n";
			
			$query=SQLClass::webtsys_query($sql_query);*/

			/*foreach(Webmodel::$arr_sql_index as $key_data => $sql_index)
			{

				echo "---Creating index for ".$key_data."\n";

				$query=SQLClass::webtsys_query($sql_index);
				$query=SQLClass::webtsys_query(Webmodel::$arr_sql_set_index[$key_data]);

			}*/

		}
		else
		if(isset(Webmodel::$model[$key]))
		{
			//Obtain all fields of model
		
			foreach(Webmodel::$model[$key]->components as $kfield => $value)
			{
		
				$allfields[$kfield]=1;
				
			}
		
			//unset($allfields['Id'.ucfirst($key)]);
			
			$arr_null['NO']='NOT NULL';
			$arr_null['YES']='NULL';

			unset($allfields[Webmodel::$model[$key]->idmodel]);
		
			$query=SQLClass::webtsys_query("describe `".$key."`");
			
			list($key_field_old, $type, $null, $key_db, $default, $extra)=SQLClass::webtsys_fetch_row($query);
		
			while(list($field, $type, $null, $key_db, $default, $extra)=SQLClass::webtsys_fetch_row($query))
			{
		
				$fields[]=$field;
				$types[$field]=$type;
				$keys[$field]=$key_db;
				
				$null_set[$field]=$arr_null[$null];
				
			}
			
			foreach($fields as $field)
			{
		
				if(isset($allfields[$field]))
				{
		
					$type=strtoupper($types[$field]);
					
					unset($allfields[$field]);
					
					if(Webmodel::$model[$key]->components[$field]->get_type_sql()!=($type.' '.$null_set[$field]))
					{
						
						$query=SQLClass::webtsys_query('alter table `'.$key.'` modify `'.$field.'` '.Webmodel::$model[$key]->components[$field]->get_type_sql());
						
						echo "Upgrading ".$field." from ".$key."...\n";
						
				
					}
					
					//Check if indexed
				
					if(Webmodel::$model[$key]->components[$field]->indexed==true && $keys[$field]=='')
					{
					
						Webmodel::$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON `'.$key.'`(`'.$field.'`);';
						Webmodel::$arr_sql_set_index[$key][$field]='';
					
					}
					
					//Check if unique
				
					if(Webmodel::$model[$key]->components[$field]->unique==true && $keys[$field]=='')
					{
					
						Webmodel::$arr_sql_unique[$key][$field]=' ALTER TABLE `'.$key.'` ADD UNIQUE (`'.$field.'`)';
						Webmodel::$arr_sql_set_unique[$key][$field]='';
					
					}

					//Set index

					if(isset(Webmodel::$model[$key]->components[$field]->related_model) && $keys[$field]=='')
					{

						
						Webmodel::$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON `'.$key.'`(`'.$field.'`);';
					
						$table_related=Webmodel::$model[$key]->components[$field]->related_model->name;
						
						$id_table_related=Webmodel::load_id_model_related(Webmodel::$model[$key]->components[$field], $model);
						
						Webmodel::$arr_sql_set_index[$key][$field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$field.'_'.$key.'IDX` FOREIGN KEY ( `'.$field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';
						

					}
					
					if(!isset(Webmodel::$model[$key]->components[$field]->related_model) && $keys[$field]!='' && Webmodel::$model[$key]->components[$field]->indexed==false && Webmodel::$model[$key]->components[$field]->unique!=true)
					{
						
						echo "---Delete index for ".$field." from ".$key."\n";
						
						$query=SQLClass::webtsys_query('DROP INDEX `index_'.$key.'_'.$field.'` ON '.$key);

					}
		
				}
		
				else
				
				{
		
					$allfields[$field]=0;
		
				}
		
			}
		
		}

		//Check if new id...

		if($key_field_old!=Webmodel::$model[$key]->idmodel)
		{

			$query=SQLClass::webtsys_query('alter table `'.$key.'` change `'.$key_field_old.'` `'.Webmodel::$model[$key]->idmodel.'` INT NOT NULL AUTO_INCREMENT');

			echo "Renaming id for this model to ".Webmodel::$model[$key]->idmodel."...\n";

		}

		//Check if new fields...
	
		foreach($allfields as $new_field => $new)
		{
				
			if($allfields[$new_field]==1)
			{
		
				$query=SQLClass::webtsys_query('alter table `'.$key.'` add `'.$new_field.'` '.Webmodel::$model[$key]->components[$new_field]->get_type_sql());

				echo "Adding ".$new_field." to ".$key."...\n";
				
				//Check if indexed
				
				if(Webmodel::$model[$key]->components[$new_field]->indexed==true)
				{
				
					Webmodel::$arr_sql_index[$key][$new_field]='CREATE INDEX `index_'.$key.'_'.$new_field.'` ON `'.$key.'`(`'.$new_field.'`);';
					Webmodel::$arr_sql_set_index[$key][$new_field]='';
				
				}

				if(isset(Webmodel::$model[$key]->components[$new_field]->related_model) )
				{

					/*echo "---Creating index for ".$new_field." from ".$key."\n";

					$query=SQLClass::webtsys_query('CREATE INDEX index_'.$key.'_'.$new_field.' ON '.$key.'('.$new_field.')');*/
					
					Webmodel::$arr_sql_index[$key][$new_field]='CREATE INDEX `index_'.$key.'_'.$new_field.'` ON `'.$key.'`(`'.$new_field.'`);';
					
					$table_related=Webmodel::$model[$key]->components[$new_field]->related_model->name;
					
					$id_table_related=Webmodel::load_id_model_related(Webmodel::$model[$key]->components[$new_field], $model);
					
					Webmodel::$arr_sql_set_index[$key][$new_field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$new_field.'_'.$key.'IDX` FOREIGN KEY ( `'.$new_field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';

				}
		
			}
		
			else
		
			{
			
				/*if(isset(Webmodel::$model[$key]->components[$new_field]->related_model) )
				{*/
				
					//Drop foreignkeyfield
					
				//Bug, need fixed.
				if($keys[$new_field]!='')
				{
				
					$query=SQLClass::webtsys_query('ALTER TABLE `'.$key.'` DROP FOREIGN KEY '.$new_field.'_'.$key.'IDX');
					
				}
					
				
				//}

				$query=SQLClass::webtsys_query('alter table `'.$key.'` drop `'.$new_field.'`');
			
				echo "Deleting ".$new_field." from ".$key."...\n";
		
			}
		
		}
		
		$arr_etable[$key]=0;
	
	}
	
	//Create Indexes...
	
	foreach(Webmodel::$arr_sql_index as $model_name => $arr_index)
	{
		foreach(Webmodel::$arr_sql_index[$model_name] as $key_data => $sql_index)
		{

			echo "---Creating index for ".$key_data." on model ".$model_name."\n";

			$query=SQLClass::webtsys_query($sql_index);
			
			if(Webmodel::$arr_sql_set_index[$model_name][$key_data]!='')
			{
				$query=SQLClass::webtsys_query(Webmodel::$arr_sql_set_index[$model_name][$key_data]);
			}

		}
	}
	
	//Create Uniques...
	
	foreach(Webmodel::$arr_sql_unique as $model_name => $arr_index)
	{
		foreach(Webmodel::$arr_sql_unique[$model_name] as $key_data => $sql_index)
		{

			echo "---Creating unique for ".$key_data." on model ".$model_name."\n";

			$query=SQLClass::webtsys_query($sql_index);
			
			if(Webmodel::$arr_sql_set_unique[$model_name][$key_data]!='')
			{
				$query=SQLClass::webtsys_query(Webmodel::$arr_sql_set_unique[$model_name][$key_data]);
			}

		}
	}
	
	/*foreach($arr_etable as $table => $value)
	{
	
		if($value==1)
		{
		
			$query=SQLClass::webtsys_query('DROP TABLE `'.$table.'`');
			
			echo 'Deleting table '.$table."\n";
		
		}
	
	}*/

}

function load_id_model_related($foreignkeyfield)
{

	//global $model;
	
	$table_related=$foreignkeyfield->related_model->name;
	
	$id_table_related='';
					
	if(!isset(Webmodel::$model[ $table_related ]->idmodel))
	{
		
		//$id_table_related='Id'.ucfirst(PhangoVar::Webmodel::$model[$key]->components[$new_field]->related_model);
		//Need load the model
		
		if(isset($foreignkeyfield->params_loading_mod['module']) && isset($foreignkeyfield->params_loading_mod['model']))
		{
		
			$model=load_model($foreignkeyfield->params_loading_mod);
			
			//obtain id
			
			$id_table_related=Webmodel::$model[ $foreignkeyfield->params_loading_mod['model'] ]->idmodel;
			
			/*unset(PhangoVar::Webmodel::$model[ $foreignkeyfield->params_loading_mod['model'] ]);
			
			unset($cache_model);*/
			
		}
	
	}
	else
	{
	
		$id_table_related=Webmodel::$model[ $table_related ]->idmodel;
	
	}
	
	if($id_table_related=='')
	{
	
		//Set standard...
	
		$id_table_related='Id'.ucfirst($table_related);
	
	}
	
	return $id_table_related;

}

?>
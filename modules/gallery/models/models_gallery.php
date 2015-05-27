<?php

Webmodel::$model['cat_gallery']=new Webmodel('cat_gallery');

Webmodel::$model['cat_gallery']->register('name', 'CharField', array(255), 1);

Webmodel::$model['image_gallery']=new Webmodel('image_gallery');

Webmodel::$model['image_gallery']->register('image', 'ImageField', array('image', PhangoVar::$base_path.'/gallery/images/', Routes::$root_url.'/gallery/images', $type='', $thumb=1, $img_width=array('mini' => 150, 'preview' => 800), $quality_jpeg=85), 1);

Webmodel::$model['image_gallery']->register('category', 'ForeignKeyField', array(Webmodel::$model['cat_gallery']));

Webmodel::$model['image_gallery']->register('name_module', 'CharField', array(255));



?>
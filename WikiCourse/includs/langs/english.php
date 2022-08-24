<?php

function lang($phrase) {
	static $lang= array(
  'MASSAGE' =>'Welcome' ,
  'ADMIN' =>' Administrator' 
);
 return $lang[$phrase];
 }

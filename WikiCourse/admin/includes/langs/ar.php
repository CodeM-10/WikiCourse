<?php

function lang($phrase) {
	static $lang= array(
  'MASSAGE' =>'مرحبا' ,
  'ADMIN' =>' المدير' 
);
 return $lang[$phrase];
 }

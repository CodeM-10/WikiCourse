<?php
	function lang($phrase) {
		static $lang = array(
			// Navbar Links
			'HOME_ADMIN' 	=> 'Wiki Cources',
			'CATEGORIES' 	=> 'Categories',
			'COURSES' 		=> 'Courses',
			'MEMBERS' 		=> 'Members',
			'COMMENTS'		=> 'Comments',
			'STATISTICS' 	=> 'Statistics',
			'LOGS' 			=> 'Logs',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => ''
		);
		return $lang[$phrase];
	}
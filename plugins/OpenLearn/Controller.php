<?php
/**
 * Extending Piwik for the JISC Track OER project.
 * 
 * @link http://piwik.org
 * @license
 * @version $Id$
 * @copyright 2012 The Open University.
 * @author N.D.Freear, 7 August 2012.
 *
 * @category Piwik_Plugins
 * @package Piwik_TrackOER
 */
#require_once PIWIK_INCLUDE_PATH .'/plugins/CoreHome/Controller.php';


/**
 *
 * @package Piwik_TrackOER
 */
class Piwik_OpenLearn_Controller extends Piwik_Controller #_Admin
#class Piwik_OpenLearn_Controller extends Piwik_CoreHome_Controller
{
	const LOGO_HEIGHT = 300;
	const LOGO_SMALL_HEIGHT = 100;

	public function __index()
	{
		#return $this->redirectToIndex('UsersManager', 'userSettings');
	}

	public function form()
	{
		#Piwik::checkUserHasSomeAdminAccess();
		#$view = Piwik_View::factory('generalSettings');
		Piwik::checkUserHasSomeViewAccess();
		$view = Piwik_View::factory('openlearn_form');

		$this->setGeneralVariablesView($view);
		$view->menu = Piwik_GetMenu();
		$view->content = '';

		// A hard-coded Labspace/ B2S example for now!
		$view->cc_code = <<<EOF
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.0/uk/deed.en_GB"
 ><img
 alt="Creative Commons Licence"
 style="border-width:0"
 src=
"http://localhost:8888/piwik/piwik.php?idsite=1&rec=1&img=cc:by-nc-sa&action_name=Learning+to+Learn&url=http%3A//labspace.open.ac.uk/Learning_to_Learn_1.0&urlref=http%3A//destination.example.org/path/to/34"
 /></a>
 <br />
 <span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Learning to Learn</span>
 by <a xmlns:cc="http://creativecommons.org/ns#" href="http://labspace.open.ac.uk/b2s" property="cc:attributionName" rel="cc:attributionURL">OpenLearn/Bridge to Success</a>
 is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.0/uk/deed.en_GB">Creative Commons Attribution-NonCommercial-ShareAlike 2.0 UK: England &amp; Wales License</a>.
 <br />
 Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="http://labspace.open.ac.uk/course/view.php?id=7442" rel="dct:source">http://labspace.open.ac.uk/Learning_to_Learn_1.0</a>.
EOF;
		
		$view->cc_code_orig = <<<EOF
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB"><img alt="Creative Commons Licence" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>.
EOF;

        $track_img = "http://track.olnet.org/track/piwik/1/cc:by-nc-sa/labspace.open.ac.uk/Learning_to_Learn_1.0?t=Learning+to+Learn";

		$view->cc_code_escaped = str_replace(array('<', "\n"), array('&lt;', ''), $view->cc_code);

		$view->cc_code_escaped = preg_replace(
			'@src="[^"]+"@', 
			'src="'.$track_img.'"', $view->cc_code_escaped);

		#return $view;
		echo $view->render();
	}

}

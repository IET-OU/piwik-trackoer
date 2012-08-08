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

/**
 *
 * @package Piwik_TrackOER
 */
class Piwik_OpenLearn extends Piwik_Plugin
{
	public function getInformation()
	{
		return array(
			'description' =>
			'* A form to add license meta-data to OpenLearn course modules. [JISC Track OER project]',
			#Piwik_Translate('CoreAdminHome_PluginDescription'),
			'homepage' => 'http://openlearn.open.ac.uk/',
			'author' => 'IET @ OU',
			'author_homepage' => 'http://iet.open.ac.uk/',
			'version' => '0.1', #Piwik_Version::VERSION,
		);
	}

	public function getListHooksRegistered()
	{
		return array(
			'TopMenu.add' => 'addTopMenu',
			#'AdminMenu.add' => 'addMenu',
			#'TaskScheduler.getScheduledTasks' => 'getScheduledTasks',
		);
	}


	public function addTopMenu()
	{
		Piwik_AddTopMenu('OpenLearn License form',
					array('module' => 'OpenLearn', 'action' => 'form'),
		#Piwik_AddAdminMenu('CoreAdminHome_MenuGeneralSettings', 
		#					array('module' => 'CoreAdminHome', 'action' => 'generalSettings'),
							#Piwik::isUserHasSomeAdminAccess(),
							Piwik::isUserHasSomeViewAccess(),
							$order = 6);
	}

}

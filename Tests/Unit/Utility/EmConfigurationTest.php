<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Test for Tx_News_Utility_EmConfiguration
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Utility_EmConfigurationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var array
	 */
	private $backupGlobalVariables;

	public function setUp() {
		$this->backupGlobalVariables = array(
			'TYPO3_CONF_VARS' => $GLOBALS['TYPO3_CONF_VARS'],
		);
		$GLOBALS['TCA'][$this->testTableName] = array('ctrl' => array());
		$GLOBALS[$this->testGlobalNamespace] = array();
	}

	public function tearDown() {
		foreach ($this->backupGlobalVariables as $key => $data) {
			$GLOBALS[$key] = $data;
		}
		unset($this->backupGlobalVariables);
	}

	/**
	 * Test if parse settings returns the settings
	 *
	 * @test
	 * @dataProvider settingsAreCorrectlyReturnedDataProvider
	 * @return void
	 */
	public function settingsAreCorrectlyReturned($expectedFields, $expected) {
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news'] = $expectedFields;
		$this->assertEquals($expected, Tx_News_Utility_EmConfiguration::parseSettings());
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function settingsAreCorrectlyReturnedDataProvider() {
		return array(
			'noConfigurationFound' => array(
				NULL, array()
			),
			'wrongConfigurationFound' => array(
				serialize('Test 123'), array()
			),
			'workingConfiguration' => array(
				serialize(array('key' => 'value')), array('key' => 'value')
			),
		);
	}


	/**
	 * Test if configuration model is correctly returned
	 *
	 * @test
	 * @dataProvider extensionManagerConfigurationIsCorrectlyReturnedDataProvider
	 * @return void
	 */
	public function extensionManagerConfigurationIsCorrectlyReturned($expectedFields, $expected) {
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news'] = $expectedFields;
		$this->assertEquals($expected, Tx_News_Utility_EmConfiguration::getSettings());
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function extensionManagerConfigurationIsCorrectlyReturnedDataProvider() {
		return array(
			'noConfigurationFound' => array(
				NULL, new Tx_News_Domain_Model_Dto_EmConfiguration(array())
			),
			'wrongConfigurationFound' => array(
				serialize('Test 123'), new Tx_News_Domain_Model_Dto_EmConfiguration(array())
			),
			'noValidPropertiesFound' => array(
				serialize(array('key' => 'value')), new Tx_News_Domain_Model_Dto_EmConfiguration(array())
			),
			'validPropertiesFound' => array(
				serialize(array('key' => 'value', 'pageModuleFieldsNews' => 'test')), new Tx_News_Domain_Model_Dto_EmConfiguration(array('pageModuleFieldsNews' => 'test'))
			),
		);
	}

}

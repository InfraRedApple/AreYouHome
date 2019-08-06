<?php
/**
 * Created by PhpStorm.
 * User: adammcelhaney
 * Date: 9/11/18
 * Time: 11:05 AM
 */

namespace App\Controllers;

use Core\View;

/**
 * MyController is a demo, authenticated controller.
 */

class MyController extends Authenticated
{

	private $name;


	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before () {
		parent::before();

		//convenience  property for the the class name
		$this->name = substr( strrchr( __CLASS__, "\\" ), 1 );

	}


	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction () {


		//Anything with an ACTION suffix will get the before() method called.

		//In this case the View page is in the Folder with the same Class name for organization and the action as the page name
		//MyController/index.html.
		// Views can be located anywhere or named anything, this is just for convenience

		View::renderTemplate( $this->name .'/index.html', array(
			'title'    => $this->name
		) );

	}


	/**
	 * Show the Test page
	 *
	 * @return void
	 */
	public function testAction()
	{
		echo 'Hello from the edit action in the Posts controller!';
		echo '<p>Route parameters: <pre>' .
			htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
	}









}
<?php

namespace App\Controllers;

use App\Auth;

/**
 * Authenticated base controller
 *
 * PHP version 7.0
 */
abstract class Authenticated extends \Core\Controller
{

	public $user;

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    protected function before()
    {
        $this->requireLogin();

	    $this->user = Auth::getUser();
    }
}

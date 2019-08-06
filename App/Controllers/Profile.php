<?php

namespace App\Controllers;

use App\Flash;
use App\Models\LastLogin;
use Core\View;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
    }

    /**
     * Show the profile
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate('Profile/show.html', [
	        'user' => $this->user,
	        'login_activity' => LastLogin::findAllForId($this->user->id)
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Changes saved');
            $this->redirect('/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
	
	////////////////////////////////////////////////
	/// PASSWORD
	////////////////////////////////////////////////
	
	/**
	 * Show the form for editing the password
	 *
	 * @return void
	 */
	public function passwordAction()
	{
		View::renderTemplate('Profile/password.html', [
			'user' => $this->user
		]);
	}
	
	/**
	 * Updates the password
	 *
	 * @return void
	 */
	public function storeAction()
	{
		// Ensure that we are posting data and not just navigated to it.
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->redirect('/');
		}
		
		$password = htmlspecialchars(trim($_POST['password']));
		$this->user->password = $password;
		$this->user->force_reset_password = 0;
		
		$result = User::password($this->user);
		
		if ($result === false) {
			// Any errors are now stored in the user object
			View::renderTemplate('Profile/password.html', [
				'user' => $this->user
			]);
		} else {
			Flash::addMessage('Password Updated.');
			$this->redirect('/profile/show');
		}
		
	}
}

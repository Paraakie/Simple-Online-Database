<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

namespace jis\a2\controller;

use jis\a2\view\View;
use jis\a2\model\UserAccountModel;

/**
 * Class UserAccountController Handles all requests related to a user e.g. creating an account and logging in
 * @package jis\a2
 * @author Isaac Clancy, Junyi Chen, Sven Gerhards
 * @Date: 25/09/2018
 * @Time: 2:43 PM
 */
class UserAccountController extends Controller
{
    /**
     * @const this constant value is the min length of password.
     */
    private const MIN_PASSWORD_LENGTH = 7;
    private const MAX_PASSWORD_LENGTH = 14;

    /**
     * Creates a new User Account if the user input is valid, otherwise returns an error
     * @param string $password The password for the new account
     * @param string $userName The name that the user uses to login
     * @param string $nickName The name to call the user by
     * @param string $email The users email
     * @return null|string If an error occurred the error message, otherwise null
     */
    private function handleSignUp(string $password, string $userName, string $nickName, string $email): ?string
    {
        //Error handling
        $passwordLength = strlen($password);
        if ($passwordLength < UserAccountController::MIN_PASSWORD_LENGTH) {
            return 'Your password must be at least '
                . UserAccountController::MIN_PASSWORD_LENGTH . ' characters long';
        }
        if ($passwordLength > UserAccountController::MAX_PASSWORD_LENGTH) {
            return 'Your password cannot be longer than '
                . UserAccountController::MIN_PASSWORD_LENGTH . ' characters long';
        }
        $userAccount = new UserAccountModel();
        if ($userAccount->loadByUserName($userName) != null) {
            return 'The account name is already in use';
        }
        //Information that user entered are correct, eligible to create a new user account
        $userAccount->setUserName($userName);
        $userAccount->setNickName($nickName);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userAccount->setPassword($passwordHash);
        $userAccount->setEmail($email);
        $userAccount->save();
        session_start();
        $_SESSION['userID'] = $userAccount->getId();

        return null;
    }

    /**
     * handles the login for letting a user create an account
     */
    public function signUp()
    {
        if (isset($_POST['signUp'])) {
            $nickName = $_POST["name"];
            $userName = $_POST["userName"];
            $password = $_POST["userPassword"];
            $email = $_POST["email"];
            $error = $this->handleSignUp($password, $userName, $nickName, $email);
            if ($error === null) {
                $this->redirect("showHome");
            } else {
                $view = new View('signUp');
                $view->addData('error', $error);
                $view->addData('name', $nickName);
                $view->addData('userName', $userName);
                $view->addData('userPassword', $password);
                $view->addData('userPassword2', $_POST["userPassword2"]);
                $view->addData('email', $email);
                echo $view->render();
            }
        } else {
            $view = new View('signUp');
            echo $view->render();
        }
    }

    /**
     * Logs in if userName and userPassword match a user account
     * @param string $userName
     * @param string $userPassword
     * @return null|string The error message if an error occurred, otherwise null
     */
    private function handleLogin(string $userName, string $userPassword): ?string
    {
        $user = (new UserAccountModel())->loadByUserName($userName);
        if ($user !== null && password_verify($userPassword, $user->getPassword())) {
            session_start();
            $_SESSION['userID'] = $user->getId();
            return null;
        } else {
            return 'Invalid user name or password';
        }
    }

    /**
     * Handles the logic for the login page
     */
    public function login()
    {
        $userName = $_POST["userName"];
        $userPassword = $_POST["userPassword"];
        if (isset($_POST['validateLogin'])) {
            $error = $this->handleLogin($userName, $userPassword);
            if ($error === null) {
                $this->redirect('showHome');
            } else {
                $view = new View('login');
                $view->addData('userName', $userName);
                $view->addData('userPassword', $userPassword);
                echo $view->addData("error", $error)->render();
            }
        } elseif (isset($_POST['signUp'])) {
            $this->redirect('signUp');
        } else {
            $view = new View('login');
            echo $view->render();
        }
    }


    /**
     * check a user name is exist or not
     * @param string $name The users userName
     */
    public function checkUserExist(string $name)
    {
        $userAccount = new UserAccountModel();
        if ($userAccount -> loadByUserName($name) != null) {
            echo "user name $name already exist !";
        }
    }

        /**
     * Gets the current user if their is one or redirects to login
     * @return UserAccountModel|null The current user if their is one, otherwise null
     */
    public static function getCurrentUser(): ?UserAccountModel
    {
        session_start();
        if (isset($_SESSION['userID'])) {
            $userId = $_SESSION['userID'];
            return (new UserAccountModel())->loadByID($userId);
        } else {
            $url = static::getUrl("login");
            header('Refresh: 3; URL=' . $url);
            echo "<p align=center style=color:red;>Please login...<br> Redirecting back to login page</p>";
            return null;
        }
    }

    /**
     *  Logout Action
     */
    public function logout()
    {
        //Deleting session
        session_start();
        session_destroy();
        $this->redirect('login');
    }
}

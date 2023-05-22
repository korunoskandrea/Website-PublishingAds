<?php
class AuthController extends Controller {
    public function loginView() {
        if(AuthService::get()->isLoggedIn()) {
            $this->redirect('/'); // v ozadju klice header
        }
        $this->view('auth/login');
    }
    public function loginSafe() {
        if (AuthService::get()->isLoggedIn()){
            $this->redirect('/');
            return;
        }
        $errorMessage = AuthService::get()->logIn($_POST['username'],$_POST['password']);
        if ($errorMessage != null) {
            $this->view('auth/login', ['error' => $errorMessage]);
            return;
        }
        $this->redirect('/');
    }
    public function logout() {
        if(AuthService::get()->isLoggedIn()) {
            AuthService::get()->logOut();
        }
        $this->redirect('/');
    }
    public function registerView() {
        if(AuthService::get()->isLoggedIn()) {
            $this->redirect('/'); // v ozadju klice header
        }
        $this->view('auth/register');
    }
    public function registerSafe() {
        if (AuthService::get()->isLoggedIn()){
            $this->redirect('/');
            return;
        }
        $errorMessage = AuthService::get()->register(
            $_POST["username"],
            $_POST["email"],
            $_POST["password"],
            $_POST["firstName"],
            $_POST["lastName"],
            $_POST["telephone"],
            $_POST["address"],
            $_POST["post"]
        );
        if($errorMessage != null) {
            $this->view('auth/register', ['error' => $errorMessage]);
        } else {
            $this->redirect('/');
        }
    }
}
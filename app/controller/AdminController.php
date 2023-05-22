<?php

class AdminController extends Controller {
    public function userList() {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        $users = DatabaseService::get()->getUsers();
        $this->view('user/admin/control-users', ['users' => $users]);
    }
    public function userDelete($id) {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        DatabaseService::get()->hardDeleteUser($id);
        $this->redirect("/user/admin");
    }
    public function userEdit($id) {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        $user = DatabaseService::get()->getUserById($id);
        $this->view("user/settings", ["user" => $user]);
    }

    public function userEditSafe($id) {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        $user = DatabaseService::get()->getUserById($id);
        DatabaseService::get()->updateUser($user, $_POST["username"], $_POST["email"], $_POST["firstName"], $_POST["lastName"], $_POST["telephone"], $_POST["post"], $_POST["address"]);
        $this->redirect("/user/admin");
    }

    public function addNewUserSafe( ) {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        try {
            DatabaseService::get()->createUser(
                $_POST["username"],
                $_POST["email"],
                $_POST["password"],
                $_POST["firstName"],
                $_POST["lastName"],
                $_POST["telephone"],
                $_POST["address"],
                $_POST["post"]
            );
            $this->redirect("/user/admin");
        } catch (Exception $e)
        {
            $this->view("user/admin/create-users", ["error" => $e->getMessage()]);
        }
    }
    public function addNewUser() {
        if (!AuthService::get()->isLoggedIn() || !AuthService::get()->getUser()->getIsAdmin()) {
            $this->redirect("/");
            return;
        }
        $this->view("user/admin/create-users");
    }
}
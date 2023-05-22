<?php

class UserController extends Controller {
    public function settingsView() {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $this->view('user/settings', ["user" => AuthService::get()->getUser()]);

    }
    public function settingsSafe() {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $user = AuthService::get()->getUser();
        DatabaseService::get()->updateUser($user, $_POST["username"], $_POST["email"], $_POST["firstName"], $_POST["lastName"], $_POST["telephone"], $_POST["post"], $_POST["address"]);
        $_SESSION["user_json"] = json_encode(DatabaseService::get()->getUserById($user->getId())->toMap());
        $this->redirect("/user/settings"); // vzame url
    }


}
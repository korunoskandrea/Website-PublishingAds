<?php

class AuthService extends Service
{
    private static ?AuthService $instance = null;
    private ?User $user = null;
    public static function get() : AuthService{
        if(self::$instance === null) {
            self::$instance = new AuthService();
        }
        if(isset($_SESSION["user_json"])) {
            self::$instance->user = User::fromMap((array)json_decode($_SESSION["user_json"]));
        }
        return self::$instance;
    }
    public function getUser(): ?User {
        return $this->user;
    }
    public function isLoggedIn () : bool{
        return $this->user !== null;
    }
    public function register(string $username, string $email,string $password, string $firstName, string $lastName, ?string $telephoneNumber, ?string $address, ?string $post): ?string {
        try {
            $user = DatabaseService::get()->createUser($username, $email, $password, $firstName, $lastName, $telephoneNumber, $address, $post);
            $_SESSION["user_json"] = json_encode($user->toMap());
            return null; // success
        } catch (Exception $e) {
            return $e->getMessage(); // something went wrong
        }
    }
    public function logIn(string $username, string $password): ?string {
        $user = DatabaseService::get()->getUserByUsernameOrEmail($username);
        if ($user !== null) {
            if(password_verify($password,$user->getPassword())) {
                $_SESSION["user_json"] = json_encode($user->toMap());
                return null; // success
            }
        }
        return  "Invalid credentials";
    }

    public function logOut() {
        session_destroy();
        unset($_SESSION['user_json']);
    }
}
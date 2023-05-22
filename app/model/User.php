<?php

class User
{
    public static string $DB_ID = 'id';
    public static string $DB_USERNAME = 'username';
    public static string $DB_PASSWORD = 'password';
    public static string $DB_FIRST_NAME = 'first_name';
    public static string $DB_LAST_NAME = 'last_name';
    public static string $DB_EMAIL = 'email';
    public static string $DB_TELEPHONE_NUMBER = 'telephone_number';
    public static string $DB_ADDRESS = 'address';
    public static string $DB_POST = 'post';
    public static string $TABLE_NAME = 'users';
    public static string $DB_IS_ADMIN = 'isAdmin';

    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private string $firstName;
    private string $lastName;
    private ?string $telephone;
    private ?string $post;
    private ?string $address;
    private bool $isAdmin;

    public function __construct(int $id, string $username, string $password, string $email, string $firstName, string $lastName, ?string $telephone, ?string $post, ?string $address, bool $isAdmin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->telephone = $telephone;
        $this->post = $post;
        $this->address = $address;
        $this->isAdmin = $isAdmin;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getTelephone(): string {
        return $this->telephone;
    }
    public function getAddress(): string {
        return $this->address;
    }
    public function getPost(): string {
        return $this->post;
    }
    public function getIsAdmin(): bool {
        return $this->isAdmin;
    }

    /* @return Ad[] */
    public  function getPublishedAds(): array {
        return DatabaseService::get()->getAdsByUserId($this->id);
    }

    /* @return Ad[] */
    public  function getRemovedAds(): array {
        return DatabaseService::get()->getDeletedUserAds($this->id);
    }
    public function toMap(): array {
        return  [
            self::$DB_ID => $this->id,
            self::$DB_USERNAME => $this->username,
            self::$DB_PASSWORD =>" ",
            self::$DB_EMAIL => $this->email,
            self::$DB_FIRST_NAME => $this->firstName,
            self::$DB_LAST_NAME => $this->lastName,
            self::$DB_TELEPHONE_NUMBER => $this->telephone,
            self::$DB_ADDRESS => $this->address,
            self::$DB_POST => $this->post,
            self::$DB_IS_ADMIN => $this->isAdmin
        ];
    }
    public static function fromMap(array $map): User {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_USERNAME],
            $map[self::$DB_PASSWORD],
            $map[self::$DB_EMAIL],
            $map[self::$DB_FIRST_NAME],
            $map[self::$DB_LAST_NAME],
            $map[self::$DB_TELEPHONE_NUMBER],
            $map[self::$DB_POST],
            $map[self::$DB_ADDRESS],
            $map[self::$DB_IS_ADMIN] ?? false
        );
    }

    public function __toString(): string {
        return "User {id: $this->id, username: $this->username, password: $this->password}";
    }
}
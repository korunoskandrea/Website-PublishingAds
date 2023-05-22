<?php

class Ad {
    public static string $DB_ID = 'id';
    public static string $DB_TITLE = 'title';
    public static string $DB_DESCRIPTION = 'description';
    public static string $DB_USER_ID = 'user_id';
    public static string $TABLE_NAME = 'ads';
    public static string $DB_CREATION_TIME = 'created_at';
    public static string $DB_IS_DELETED = 'is_deleted';

    private int $id;
    private string $title;
    private string $description;
    private int $userId;
    private string $createdAt;
    private bool $isDeleted;

    public function __construct(int $id, string $title, string $description, int $userId, string $createdAt, bool $isDeleted)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
        $this->isDeleted = $isDeleted;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getUser(): User
    {
        return DatabaseService::get()->getUserById($this->userId);
    }


    /* @return Category[] */
    public  function getCategories(): array {
        return DatabaseService::get()->getAdCategories($this);
    }

    public  function containsCategory(Category $searchCategory): bool {
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            if ($category->getId() === $searchCategory->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /* @return Image[] */
    public function getImages(): array {
        return DatabaseService::get()->getImagesFromAd($this);
    }

    public static function fromMap(array $map): Ad {
        return new self(
            $map[self::$DB_ID],
            strip_tags($map[self::$DB_TITLE]),
            strip_tags($map[self::$DB_DESCRIPTION]),
            $map[self::$DB_USER_ID],
            $map[self::$DB_CREATION_TIME],
            $map[self::$DB_IS_DELETED]
        );
    }

    public function __toString(): string
    {
        return "Ad {id: $this->id, title: \"$this->title\", description: \"$this->description\", user_id: $this->userId}";
    }
}
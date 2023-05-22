<?php

class Category {
    public static string $DB_ID = 'id';
    public static string $DB_CATEGORY = 'category';

    public static string $TABLE_NAME = 'category';

    private int $id;
    private string $category;

    public function __construct(int $id, string $category) {
        $this->id = $id;
        $this->category = $category;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getId(): int {
        return $this->id;
    }

    public static function fromMap(array $map): Category {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_CATEGORY]
        );
    }
}
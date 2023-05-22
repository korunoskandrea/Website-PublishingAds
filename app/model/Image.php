<?php

class Image {
    public static string $DB_ID = 'id';
    public static string $DB_BYTES = 'bytes';

    public static string $TABLE_NAME = 'image';

    private int $id;
    private string $bytes;
    public function __construct(int $id, string $bytes) {
        $this->id = $id;
        $this->bytes = $bytes;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getBytes(): string {
        return $this->bytes;
    }

    public static function fromMap(array $map): Image {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_BYTES]
        );
    }

}
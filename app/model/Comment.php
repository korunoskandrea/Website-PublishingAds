<?php
class Comment {
    public static string $DB_ID = 'id';
    public static string $DB_USER_ID = 'user_id';
    public static string $DB_AD_ID = 'ad_id';
    public static string $DB_TEXT_COMMENT = 'text_comment';
    public static string $DB_CREATED_AT = 'created_at';
    public static string $DB_UDATED_AT = 'updated_at';
    public static string $DB_IP = 'ip';
    public static string $TABLE_NAME = 'comment';

    private int $id;
    private int $userId;
    private int $adId;
    private string $textComment;
    private string $createdAt;
    private string $updatedAt;
    private string $ip;

    public function __construct(int $id, int $userId, int $adId, string $textComment, string $createdAt, string $updatedAt, string $ip)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->adId = $adId;
        $this->textComment = $textComment;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->ip = $ip;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getAdId(): int
    {
        return $this->adId;
    }
    public function getTextComment(): string
    {
        return $this->textComment;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public static function fromMap(array $map): Comment {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_USER_ID],
            $map[self::$DB_AD_ID],
            $map[self::$DB_TEXT_COMMENT],
            $map[self::$DB_CREATED_AT],
            $map[self::$DB_UDATED_AT],
            $map[self::$DB_IP],
        );
    }
    public function toMap(): array{
        return [
            self::$DB_ID => $this->id,
            self::$DB_USER_ID => $this->userId,
            self::$DB_AD_ID => $this->adId,
            self::$DB_TEXT_COMMENT => $this->textComment,
            self::$DB_CREATED_AT => $this->createdAt,
            self::$DB_UDATED_AT => $this->updatedAt,
            self::$DB_IP => $this->ip
        ];
    }
}
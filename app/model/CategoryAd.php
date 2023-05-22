<?php

class CategoryAd{
    public static string $DB_ID = 'id';
    public static string $DB_CATEGORY_ID = 'category_id';
    public static string $DB_AD_ID = 'ad_id';
    public static string $TABLE_NAME = 'category_ad';


    private int $id;
    private string $categoryId;
    private string $adId;

    public function __construct(int $id, string $categoryId, string $adId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->adId = $adId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
    public function getAdId(): string
    {
        return $this->adId;
    }
    public static function fromMap(array $map): CategoryAd {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_CATEGORY_ID],
            $map[self::$DB_AD_ID],
        );
    }
    
}
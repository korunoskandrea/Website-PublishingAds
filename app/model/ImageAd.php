<?php

class ImageAd {
    public static string $DB_ID = 'id';
    public static string $DB_IMAGE_ID = 'image_id';
    public static string $DB_AD_ID = 'ad_id';

    public static string $TABLE_NAME = 'image_ad';

    private int $id;
    private int $image_id;
    private int $ad_id;

    public function __construct(int $id, int $image_id, int $ad_id)
    {
        $this->id = $id;
        $this->image_id = $image_id;
        $this->ad_id = $ad_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageId(): int
    {
        return $this->image_id;
    }

    public function getAdId(): int
    {
        return $this->ad_id;
    }

    public static function fromMap(array $map): ImageAd {
        return new self(
            $map[self::$DB_ID],
            $map[self::$DB_IMAGE_ID],
            $map[self::$DB_AD_ID]
        );
    }

}
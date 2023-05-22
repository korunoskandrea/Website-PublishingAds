<?php
class DatabaseService extends Service {

    private static  ?DatabaseService $instance = null;
    private $connection;
    protected function __construct() { // povezava z bazo
        parent::__construct(); // kliemo konstruktor starsa 
        try{
            $this->connection = new  PDO("mysql:host=127.0.0.1;dbname=vaja1","root","andrea123");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // za napake pri delo z base
        } catch (PDOException $ex){
            die("Error while connecting with database : " .$ex->getMessage());
        }
    }
    public static function get() : DatabaseService { // ustvarimo objekt od DatabaseService (na drug nacin ne moremo, kjer ima privatni konstruktor, ki je v Service)
        if(self::$instance === null) {
            self::$instance = new DatabaseService();

        }
        return self::$instance;
    }

    /** @return Ad[] */
    public function getAdsList() : array {
        $query = new Query(AD::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Ad::$DB_IS_DELETED], [false], Ad::$DB_CREATION_TIME);
        $statement->execute();
        $adList = []; // array tipa Ad
        foreach ($statement->fetchAll() as $row){
            $adList[] = Ad::fromMap($row); // push_back v array
        }
        return $adList;
    }

    public function getAddById(int | string | null $id): ?Ad {
        // check if id is int
        if (!is_numeric($id)) return null;
        $idInt = intval($id);
        $query = new Query(Ad::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Ad::$DB_ID, Ad::$DB_IS_DELETED], [$idInt, false]);
        $statement->execute();
        if ($statement->rowCount() == 1) { // preverimo ce smo na sploh dobili ena vrstico
            $row = $statement->fetch();
            return Ad::fromMap($row);
        }
        return null; // ce nismo dobili 1 vrstico bp null
    }

    public function getDeletedAddById(int | string | null $id): ?Ad {
        // check if id is int
        if (!is_numeric($id)) return null;
        $idInt = intval($id);
        $query = new Query(Ad::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Ad::$DB_ID, Ad::$DB_IS_DELETED], [$idInt, true]);
        $statement->execute();
        if ($statement->rowCount() == 1) { // preverimo ce smo na sploh dobili ena vrstico
            $row = $statement->fetch();
            return Ad::fromMap($row);
        }
        return null; // ce nismo dobili 1 vrstico bp null
    }

    /** @return Ad[] */
    public function getAdsByUserId(int | string | null $userId ): array {
        // check if id is int
        if (!is_numeric($userId)) return [];
        $userIdInt = intval($userId);
        $query = new Query(Ad::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([AD::$DB_USER_ID, Ad::$DB_IS_DELETED], [$userIdInt, false], Ad::$DB_CREATION_TIME);
        $statement->execute();
        $adsList = [];
        foreach ($statement->fetchAll() as $row) {
            $adsList[] = Ad::fromMap($row);
        }
        return $adsList;
    }

    /** @return Ad[] */
    public function getDeletedUserAds(int | string | null $userId ): array {
        // check if id is int
        if (!is_numeric($userId)) return [];
        $userIdInt = intval($userId);
        $query = new Query(Ad::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([AD::$DB_USER_ID, Ad::$DB_IS_DELETED], [$userIdInt, true]);
        $statement->execute();
        $adsList = [];
        foreach ($statement->fetchAll() as $row) {
            $adsList[] = Ad::fromMap($row);
        }
        return $adsList;
    }

    public function getUserById(int | string | null $id): ?User {
        if (!is_numeric($id)) return null;
        $idInt = intval($id);
        $query = new Query(User::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([User::$DB_ID], [$idInt]);
        $statement->execute();
        if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            return User::fromMap($row);
        }
        return null;
    }

    public function getUserByUsernameOrEmail(string $username): ?User {
        if (strlen(trim($username)) === 0) return null;
        $query = new Query(User::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingOr([User::$DB_EMAIL, User::$DB_USERNAME], [$username, $username]);
        $statement->execute();
        if ($statement->rowCount() == 1) {
            return User::fromMap($statement->fetch());
        }
        return null;
    }

    /** @throws Exception */
    public function createUser(string $username, string $email, string $password, string $firstName, string $lastName, ?string $telephoneNumber, ?string $address, ?string $post): User {
        // Check if username / email already exists
        if ($this->getUserByUsernameOrEmail($username) !== null) throw new Exception("Username or email already exists");
        $query = new Query(User::$TABLE_NAME, $this->connection);
        $statement = $query->insert(
            [User::$DB_USERNAME, User::$DB_EMAIL, User::$DB_PASSWORD, User::$DB_FIRST_NAME, User::$DB_LAST_NAME, User::$DB_TELEPHONE_NUMBER, User::$DB_ADDRESS, User::$DB_POST],
            [$username, $email, password_hash($password, PASSWORD_BCRYPT), $firstName, $lastName, $telephoneNumber, $address, $post],
        );
        $statement->execute();
        if($statement->rowCount() >= 1){
            $statement = $query->selectWhereUsingOr([User::$DB_ID],[$this->connection->lastInsertId()]);
            $statement->execute();
            return User::fromMap($statement->fetch());
        }
        throw new Exception("Unexpected error while trying to register");
    }

    public function insertAdWithDetail(string $title, string $description, array $cathegoryStingIds, array $imagesBytes): bool {
        try {
            $newAd = $this->insertAd($title, $description);
            foreach ($cathegoryStingIds as $catId) {
                $this->insertCategoryAd($newAd, intval($catId));
            }
            foreach ($imagesBytes as $imageBytes) {
                $image = $this->insertImage($imageBytes);
                $this->insertImageAd($newAd, $image);
            }
            return true;
        } catch (Exception $er) {
            echo "Error: ".$er->getMessage();
            return false;
        }

    }

    /** @return Category[] */
    public function getCategories(): array {
        $query = new Query(Category::$TABLE_NAME, $this->connection);
        $statement = $query->selectAll();
        $statement->execute();
        $categoryList = [];
        foreach ($statement->fetchAll() as $row) {
            $categoryList[] = Category::fromMap($row);
        }

        return $categoryList;
    }

    /** @return Category[] */
    public function getAdCategories(Ad $ad): array {
        $categories = [];
        $query = new Query(Category::$TABLE_NAME, $this->connection);
        foreach ($this->getCategoryAdList($ad) as $categoryAd) { // vse zapise iz vmesne tabele
            $category = $this->getCategoryById($categoryAd->getCategoryId());
            if ($category !== null) $categories[] = $category;
        }
        return  $categories;
    }

    public function getCategoryById(int $id): ?Category {
        $query = new Query(Category::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Category::$DB_ID], [$id]);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return Category::fromMap($statement->fetch());
        }
        return null;
    }

    /** @return CategoryAd[] */
    public function getCategoryAdList(Ad $ad): array {
        $categories = [];
        $query = new Query(CategoryAd::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([CategoryAd::$DB_AD_ID], [$ad->getId()]);
        $statement->execute();
        foreach ($statement->fetchAll() as $row) {
            $categories[] = CategoryAd::fromMap($row);
        }
        return  $categories;
    }


    /* @return Image[] */
    public function getImagesFromAd(Ad $ad): array {
        $images = [];
        $query = new Query(Image::$TABLE_NAME, $this->connection);
        foreach ($this->getImageAdValuesFromAdId($ad->getId()) as $imageAd) { // vse zapise iz vmesne tabele
            $statement = $query->selectWhereUsingAnd([Image::$DB_ID], [$imageAd->getImageId()]);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $images[] = Image::fromMap($statement->fetch());
            }
        }
        return  $images;
    }

    public function softDeleteAd(Ad $ad) {
        $query = "UPDATE ".Ad::$TABLE_NAME." SET ".Ad::$DB_IS_DELETED." = true WHERE ".Ad::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $id = $ad->getId();
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function hardDeleteAd(Ad $ad) {
        $query = "DELETE FROM ".Ad::$TABLE_NAME." WHERE ".Ad::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $id = $ad->getId();
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function restoreAd(Ad $ad) {
        $query = "UPDATE ".Ad::$TABLE_NAME." SET ".Ad::$DB_IS_DELETED." = false WHERE ".Ad::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $id = $ad->getId();
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function updateAd(Ad $ad, string $title, string $description, array $categories, array $removedImages, array $newImages) {
        $query = "UPDATE ".Ad::$TABLE_NAME." SET ".Ad::$DB_TITLE." = :title, ".Ad::$DB_DESCRIPTION." = :description WHERE ".Ad::$DB_ID ." = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':description', $description);
        $id = $ad->getId();
        $statement->bindParam(':id', $id);
        $statement->execute();

        $this->updateAdCategories($ad, $categories);
        foreach ($removedImages as $removedImageId) {
            $this->deleteImageAd($ad, intval($removedImageId));
        }
        foreach ($newImages as $newImage) {
            try {
                $image = $this->insertImage($newImage);
                $this->insertImageAd($ad, $image);
            } catch (Exception) {}
        }

    }

    /* @return ImageAd[] */
    private function getImageAdValuesFromAdId(int $adId) {
        $adImage = [];
        $query = new Query(ImageAd::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([ImageAd::$DB_AD_ID], [$adId]);
        $statement->execute();
        foreach ($statement->fetchAll() as $row) {
            $adImage[] = ImageAd::fromMap($row);
        }
        return $adImage;
    }

    /** @throws Exception */
    private function insertAd(string $title, string $description): Ad  {
        $query = new Query(Ad::$TABLE_NAME, $this->connection);
        $statement = $query->insert(
            [Ad::$DB_TITLE, Ad::$DB_DESCRIPTION, Ad::$DB_USER_ID],
            [$title, $description, AuthService::get()->getUser()->getId()]
        );
        $statement->execute();
        if($statement->rowCount() >= 1) {
            $statement = $query->selectWhereUsingOr([Ad::$DB_ID],[$this->connection->lastInsertId()]);
            $statement->execute();
            return Ad::fromMap($statement->fetch());
        }
        throw new Exception("Unexpected error while publishing an ad");
    }

    /** @throws Exception */
    private function insertCategoryAd(Ad $ad, int $catId) {
        $query = new Query(CategoryAd::$TABLE_NAME, $this->connection);
        $statement = $query->insert(
            [CategoryAd::$DB_AD_ID, CategoryAd::$DB_CATEGORY_ID],
            [$ad->getId(), $catId]
        );
        $statement->execute();
        if($statement->rowCount() < 1) {
            throw new Exception("Unexpected error while publishing an ad");
        }
    }

    /** @throws Exception */
    private function insertImage(string $bytes) {
        $query = new Query(Image::$TABLE_NAME, $this->connection);
        $statement = $query->insert([Image::$DB_BYTES], [$bytes]);
        $statement->execute();
        if ($statement->rowCount() >= 1) {
            $statement = $query->selectWhereUsingOr([Image::$DB_ID],[$this->connection->lastInsertId()]);
            $statement->execute();
            return Image::fromMap($statement->fetch());
        }
        throw new Exception("Unexpected error while publishing an image");
    }

    /** @throws Exception */
    private function insertImageAd(Ad $ad, Image $image) {
        $query = new Query(ImageAd::$TABLE_NAME, $this->connection);
        $statement = $query->insert(
            [ImageAd::$DB_AD_ID, ImageAd::$DB_IMAGE_ID],
            [$ad->getId(), $image->getId()]
        );
        $statement->execute();
        if($statement->rowCount() < 1) {
            throw new Exception("Unexpected error while publishing an ad");
        }
    }

    private function deleteCategoryAd(Ad $ad, Category $category) {
        $query = "DELETE FROM ".CategoryAd::$TABLE_NAME." WHERE ".CategoryAd::$DB_AD_ID." = :adId AND ".CategoryAd::$DB_CATEGORY_ID." = :catId";
        $statement = $this->connection->prepare($query);

        $adId = $ad->getId();
        $catId = $category->getId();

        $statement->bindParam(':adId', $adId);
        $statement->bindParam(':catId', $catId);
        $statement->execute();
    }

    private function deleteImageAd(Ad $ad, int $imageId) {
        $query = "DELETE FROM ".ImageAd::$TABLE_NAME." WHERE ".ImageAd::$DB_AD_ID." = :adId AND ".ImageAd::$DB_IMAGE_ID." = :imgId";
        $statement = $this->connection->prepare($query);

        $adId = $ad->getId();

        $statement->bindParam(':adId', $adId);
        $statement->bindParam(':imgId', $imageId);
        $statement->execute();
    }

    private function updateAdCategories(Ad $ad, array $newCategories) {
        // Katere kategorije da se izbrisejo?
        $currentAdCategories = $ad->getCategories();
        foreach ($currentAdCategories as $adCategory) {
            $delete = true;
            foreach ($newCategories as $newCategory) {
                if ($adCategory->getId() == $newCategory) {
                    $delete = false;
                    break;
                }
            }
            if ($delete) {
                $this->deleteCategoryAd($ad, $adCategory);
            }
        }
        // Insert new adCategory
        foreach ($newCategories as $newCategory) {
            $insert = true;
            foreach ($currentAdCategories as $adCategory) {
                if ($newCategory == $adCategory->getId()) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                try {
                    $this->insertCategoryAd($ad, $newCategory);
                } catch (Exception $ex) {}
            }
        }
    }

    public function updateUser(User $user, string $username, string $email, string $firstName, string $lastName, ?string $telephone, ?string $post, ?string $address){
        $query = "UPDATE ".User::$TABLE_NAME." SET ".User::$DB_USERNAME." = :username, ".User::$DB_EMAIL." = :email, ".User::$DB_FIRST_NAME." = :firstName, ".User::$DB_LAST_NAME." = :lastName, ".User::$DB_POST." = :post, ".User::$DB_TELEPHONE_NUMBER." = :telephone, ".User::$DB_ADDRESS." = :address WHERE ".User::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':firstName', $firstName);
        $statement->bindParam(':lastName', $lastName);
        $statement->bindParam(':telephone', $telephone);
        $statement->bindParam(':post', $post);
        $statement->bindParam(':address', $address);
        $id = $user->getId();
        $statement->bindParam(':id',$id);
        $statement->execute();
    }

    public function getUsers() : array {
        $query = new Query(User::$TABLE_NAME, $this->connection);
        $statement = $query->selectAll();
        $statement->execute();
        $userList = [];
        foreach ($statement->fetchAll() as $row){
            $userList[] = User::fromMap($row);
        }
        return $userList;
    }

    public function hardDeleteUser(string $idStr) {
        $query = "DELETE FROM ".User::$TABLE_NAME." WHERE ".User::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $intval = intval($idStr);
        $statement->bindParam(':id', $intval);
        $statement->execute();
    }
    /* @return Comment[] */
    public function getFirstFiveComments() {
        $query = "SELECT * FROM ".Comment::$TABLE_NAME." ORDER BY ".Comment::$DB_CREATED_AT." DESC LIMIT 5";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $comments = [];
        foreach ($statement->fetchAll() as $row) {
            $comments[] = Comment::fromMap($row);
        }
        return $comments;
    }
    /* @return Comment[] */
    public function getAllAdComments(int | string | null $adId): array {
        $query = new Query(Comment::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Comment::$DB_AD_ID], [$adId]);
        $statement->execute();
        if ($statement->rowCount() >= 1) {
            $comments = [];
            foreach ($statement->fetchAll() as $row) {
                $comments[] = Comment::fromMap($row);
            }
            return $comments;
        }
        return [];
    }

    /** @throws Exception */
    public function  createComment(int | string $adId, int | string $userId, string $commentText, string $ip ) {
        $query = new Query(Comment::$TABLE_NAME, $this->connection);
        $statement = $query->insert(
            [Comment::$DB_AD_ID, Comment::$DB_USER_ID, Comment::$DB_TEXT_COMMENT, Comment::$DB_IP],
            [$adId,$userId,$commentText,$ip]
        );
        $statement->execute();
        if($statement->rowCount() >= 1){
            $statement = $query->selectWhereUsingOr([Comment::$DB_ID],[$this->connection->lastInsertId()]);
            $statement->execute();
            return Comment::fromMap($statement->fetch());
        }
        throw new Exception("Unexpected error while posting your comment");
    }

    public function doesCommentBelongToUser(string | int $userId, string | int $commentId): bool {
        $query = new Query(Comment::$TABLE_NAME, $this->connection);
        $statement = $query->selectWhereUsingAnd([Comment::$DB_USER_ID, Comment::$DB_ID], [$userId, $commentId]);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function deleteComment(string | int $commentId) {
        $query = "DELETE FROM ".Comment::$TABLE_NAME." WHERE ".Comment::$DB_ID." = :id";
        $statement = $this->connection->prepare($query);
        $id = intval($commentId);
        $statement->bindParam(':id', $id);
        $statement->execute();
    }
}
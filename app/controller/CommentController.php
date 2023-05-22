<?php

class CommentController extends Controller {
    public function showComments() {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $comments = ['comments' => []];
        foreach (DatabaseService::get()->getFirstFiveComments() as $comment) {
            $comments['comments'][] = $comment->toMap();
        }
        $this->view('api/comment', ['data' => json_encode($comments)]);
    }

    public function showAdComments(string $adId) {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $comments = ['comments' => []];
        foreach (DatabaseService::get()->getAllAdComments($adId) as $comment) {
            $comments['comments'][] = $comment->toMap();
        }
        $this->view('api/comment', ['data' => json_encode($comments)]);
    }

    public function postComment(string $adId) { // api postman
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        if (!AuthService::get()->isLoggedIn()) {
            http_response_code(401);
            $this->view('api/comment', ['data' => json_encode(['error' => 'You have to be logged-in'])]);
        } else {
            $data = json_decode(file_get_contents('php://input')); // za post da je veljaven za json; json string ki ga dobima pretvorimo v jason objekt
            try {
                $newComment = DatabaseService::get()->createComment($adId, AuthService::get()->getUser()->getId(), $data->comment, $data->ip);
                $comment = ["comment" => $newComment->toMap()];
                $this->view('api/comment', ['data' => json_encode($comment)]);
            } catch (Exception $e) {
                http_response_code(500);
                $this->view('api/comment', ['data' => json_encode(['error' => $e->getMessage()])]);
            }
        }
    }

    public function deleteComment(string $commentId) {
        if (!AuthService::get()->isLoggedIn()) {
            http_response_code(401);
            $this->view('api/comment', ['data' => json_encode(['error' => 'You have to be logged-in'])]);
        }
        if (!DatabaseService::get()->doesCommentBelongToUser(AuthService::get()->getUser()->getId(), $commentId)) {
            http_response_code(401);
            $this->view('api/comment', ['data' => json_encode(['error' => 'You can\'t delete this comment'])]);
        }
        DatabaseService::get()->deleteComment($commentId);
        $this->view('api/comment', ['data' => json_encode(['message' => "Deleted successfully"])]);
    }
}
<?php

class AdsController extends Controller {
    public function adList() {
        $ads = DatabaseService::get()->getAdsList();
        $this->view('ads/ad-list', ['ads' => $ads]); // 'ads' je ime preko katero dostopamo do podatke v ad-list.view
    }
    public function adDetail($id) {
        $adDetail = DatabaseService::get()->getAddById($id);
        $this->view('ads/ad-detail', ['ad' => $adDetail]);
    }
    public function adsUser(){
        if(AuthService::get()->isLoggedIn()) {
            $adsUser = AuthService::get()->getUser()->getPublishedAds();
            $this->view('ads/ad-user', ['ads' => $adsUser]);
        } else {
            $this->redirect('/');
        }
    }

    public function adEditView($id){
        if(AuthService::get()->isLoggedIn()) {
            $ad = DatabaseService::get()->getAddById($id);
            $this->view('ads/ad-edit', ['ad' => $ad]);
        } else {
            $this->redirect('/');
        }
    }

    public function adEditSafe($id){
        if(AuthService::get()->isLoggedIn()) {
            $ad = DatabaseService::get()->getAddById($id);
            $files = [];
            foreach ($_FILES['image']['tmp_name'] as $file) {
                $files[] = file_get_contents($file);
            }
            DatabaseService::get()->updateAd($ad, $_POST['ad-title'], $_POST['ad-description'], $_POST['categories'] ?? [], $_POST['removedImages'] ?? [], $files);
            $this->redirect('/ads/my-ads');
        } else {
            $this->redirect('/');
        }
    }

    public function adSoftDelete($id) {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $ad = DatabaseService::get()->getAddById($id ?? null); // preveri ce je get id null in ce ni uporabi njegova vrednost drugace uporabi vrednost null
        if (!$ad) {
            $this->redirect("/");
        } else {
            DatabaseService::get()->softDeleteAd($ad);
            $this->redirect('/ads/my-ads');
        }

    }

    public function adDeletedView() {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $ads = DatabaseService::get()->getDeletedUserAds(AuthService::get()->getUser()->getId()); // preveri ce je get id null in ce ni uporabi njegova vrednost drugace uporabi vrednost null
        $this->view("ads/ad-deleted", ["ads" => $ads]);
    }
    public function adHardDelete($id) {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $ad = DatabaseService::get()->getDeletedAddById($id ?? null); // preveri ce je get id null in ce ni uporabi njegova vrednost drugace uporabi vrednost null
        if (!$ad)  {
            $this->redirect("/");
        } else {
            DatabaseService::get()->hardDeleteAd($ad);
            $this->redirect("/ads/delete-ad");
        }

    }


    public function adRestore($id) {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $ad = DatabaseService::get()->getDeletedAddById($id ?? null); // preveri ce je get id null in ce ni uporabi njegova vrednost drugace uporabi vrednost null
        if (!$ad)  {
            $this->redirect("/");
        } else {
            DatabaseService::get()->restoreAd($ad);
            $this->redirect("/ads/delete-ad");
        }

    }

    public function adPublishView() {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $this->view('ads/publish');
    }

    public function adPublishSafe() {
        if (!AuthService::get()->isLoggedIn()) {
            $this->redirect("/");
            return;
        }
        $files = [];
        foreach ($_FILES['image']['tmp_name'] as $file) {
            $files[] = file_get_contents($file);
        }
        DatabaseService::get()->insertAdWithDetail($_POST['ad-name'], $_POST['ad-description'], $_POST['categories'], $files);
        $this->redirect('/ads/my-ads');
    }
}
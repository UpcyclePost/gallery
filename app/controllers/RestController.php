<?php

class RestController extends ControllerBase
{
    public function populateIndexAction() {
        $this->view->disable();

        $posts = Post::find(Array("category_ik is not null AND indexed = 0 AND status = 'posted' AND visible = 1"));
        if (count($posts) > 0) {
            $documents = [];

            $update = $this->solr->createUpdate();

            foreach ($posts AS $post) {
                $document = $update->createDocument();
                $document->ik = $post->ik;
                $document->id = $post->id;
                $document->type = $post->type;
                $document->category = $post->category_ik;
                $document->categoryTitle = $post->Category->title;
                $document->user = $post->user_ik;
                $document->userName = $post->User->user_name;
                $document->tags = explode(',', $post->tags);
                $document->title = $post->title;
                $document->description = $post->description;
                $document->visible = $post->visible;
                $document->views = $post->views;
                $document->likes = $post->likes;
                $document->comments = $post->comments;
                $document->influence = $post->influence();
                $document->posted = strtotime($post->created);

                $documents[] = $document;

                $post->indexed = 1;
                $post->update();
            }

            $update->addDocuments($documents);
            $update->addCommit();

            $this->solr->update($update);
        }

        $posts = Post::find(Array("indexed = 0 AND visible = 0 AND status = 'deleted'"));
        if (count($posts) > 0) {
            foreach ($posts AS $post) {
                $update = $this->solr->createUpdate();
                $update->addDeleteById($post->ik);
                $update->addCommit();
                $this->solr->update($update);

                $post->indexed = 1;
                $post->update();
            }
        }

        $upCategories = Helpers::getCategoryList();
        $categories = [];
        foreach ($upCategories AS $category)
        {
            $categories[$category['title']] = $category['ik'];
        }
        /**
         * Add PrestaShop Post ID (id)
         */
        $prestashopPosts = (new \Up\Services\PrestashopIntegrationService())->findRecentProducts(\false);
        if (count($prestashopPosts) > 0)
        {
            $documents = [];
            $update = $this->solr->createUpdate();

            foreach ($prestashopPosts AS $post)
            {
                $document = $update->createDocument();
                //Products will start with a 5, and be at least 9 digit numbers.
                //@TODO: We need to make the IDs work with the Idea IDs to avoid collisions.
                //@TODO: the "ik" field is unique.
                $document->ik = (int)sprintf('5%s', str_pad($post['ik'], 8, '0', STR_PAD_LEFT));
                $document->id = $post['ik'];
                $document->type = 'market';
                $document->category = (isset($categories[$post['categoryTitle']])) ? $categories[$post['categoryTitle']] : $post['categoryIk'];
                $document->categoryTitle = $post['categoryTitle'];
                $document->user = $post['user'];
                $document->userName = $post['shopName'];
                $document->tags = explode(',', $post['tags']);
                $document->title = $post['title'];
                $document->description = str_replace(chr(16), '', $post['description']);
                $document->posted = strtotime($post['posted']);
                $document->visible = 1;
                $document->views = $post['views'];
                $document->likes = 0;
                $document->comments = 0;
                $document->influence = 0;

                $documents[] = $document;
            }

            $update->addDocuments($documents);
            $update->addCommit();

            $this->solr->update($update);
        }
    }

    public function createThumbnailsAction()
    {
        $this->view->disable();
        return;
        die();
        
        $users = User::find([
                       'conditions' => 'custom_background IS NOT NULL'
                   ]);

        if ($users)
        {
            foreach ($users AS $user)
            {
                $currentBackground = sprintf('%s%s', $this->config->application->profileImageDir, $user->custom_background);

                $thumbnailFile = sprintf('%sthumb-%s', $this->config->application->profileImageDir, $user->custom_background);
                if (!file_exists($thumbnailFile))
                {
                    $imageProcessingService = new ImageProcessingService($currentBackground);
                    list($width, $height, $type, $attr) = @getimagesize($currentBackground);

                    $imageProcessingService->createThumbnail($thumbnailFile, ($width >= 244) ? 244 : $width);
                }
            }
        }
    }

    public function setWebsitesAction()
    {
        $this->view->disable();
        return;
        die();

        $users = User::find([
            'conditions'    => 'websites is null and (facebook is not null or twitter is not null)'
                            ]);

        if ($users)
        {
            foreach ($users AS $user)
            {
                $websites = [];
                if ($user->facebook)
                {
                    $websites[] = ['type' => 'facebook', 'url' => $user->facebook];
                }
                if ($user->twitter)
                {
                    $websites[] = ['type' => 'twitter', 'url' => $user->twitter];
                }

                $user->websites = json_encode($websites);
                $user->save();
            }
        }
    }

    public function importAction() {
        $this->view->disable();
        return;
        die();

        $str = 'basketball & misc junk yard fountain';
        echo preg_replace('/[^a-zA-Z0-9 ]/', '-', $str);

        $dir = $this->config->application->uploadDir . 'import/';
        $file = $dir . 'update.csv';

        $categories = Helpers::getCategoryList();

        $filter = function($value) { return (trim($value) != ''); };

        $i = 0;

        $h = fopen($file, 'r');
        while (($s = fgetcsv($h)) !== false) {
            if ($i > 0) {
                $i_id = $s[0];
                $i_title = $s[1];
                $i_category = $s[2];
                $i_description = $s[3];
                $i_tags = [$s[4],$s[5],$s[6],$s[7],$s[8]];

                //$tags = array_filter($i_tags, $filter);

                /*if (count($tags) > 0) {
                    //$post = Post::find()
                    $posts = Post::find(['title = ?0', 'bind' => [$i_title]]);

                    if (count($posts) > 0) {
                        foreach ($posts AS $post) {
                            if (empty($post->tags)) {
                                $post->tags = implode(',', $tags);
                                $post->indexed = 0;
                                $post->update();

                                echo $post->ik . '<br />';
                            }
                        }
                    }
                }*/

                $category = strtolower(preg_replace('/[^a-zA-Z]/', '', $i_category));
                if ($category == 'artscrafts' || $category == 'arts') $category = 'art';
                if ($category == 'glass') {
                    if (isset($categories[$category])) {
                        $posts = Post::find(['title = ?0', 'bind' => [$i_title]]);

                        if (count($posts) > 0) {
                            foreach ($posts AS $post) {
                                //if (!($post->Category->slug == 'glass')) {
                                    $post->category_ik = $categories[$category]['ik'];
                                    $post->indexed = 0;
                                    $post->save();

                                    echo $post->ik . '<br />';
                                //}
                            }
                        }
                    }
                }
            }
            $i++;
        }

        /*$file = $dir . 'import.csv';

        require_once($this->config->application->libraryDir . 'phpThumb/phpthumb.class.php');

        $h = fopen($file, 'r');
        $i = 0;
        while (($s = fgetcsv($h)) !== false) {
            if ($i > 0) {

                $permanentFile = $dir . $s[0] . '.png';
                if (!file_exists($permanentFile)) {
                    $permanentFile = $dir . $s[0] . '.bmp';

                    if (!file_exists($permanentFile)) {
                        $permanentFile = $dir . $s[0] . '.jpg';

                        if (!file_exists($permanentFile)) {
                            $permanentFile = $dir . $s[0] . '.JPG';

                            if (!file_exists($permanentFile)) {
                                $permanentFile = $dir . $s[0] . '.gif';
                            }
                        }
                    }
                }

                if (file_exists($permanentFile)) {
                    $postModel = new Post();
                    $postModel->created = date('Y-m-d H:i:s');
                    $postModel->visible = 1;
                    $postModel->indexed = 0;
                    $postModel->type = 'idea';
                    $postModel->status = 'created';
                    $postModel->views = 0;
                    $postModel->likes = 0;
                    $postModel->comments = 0;
                    $postModel->shares = 0;
                    $postModel->reports = 0;
                    $postModel->description = $s[3];
                    $postModel->title = $s[1];
                    $postModel->user_ik = 3;
                    $postModel->tags = '';

                    $categories = Helpers::getCategoryList();
                    $category = strtolower(preg_replace('/[^a-zA-Z]/', '', $s[2]));
                    if ($category == 'artscrafts' || $category == 'arts') $category = 'art';

                    if (isset($categories[$category])) {
                        $postModel->category_ik = $categories[$category]['ik'];

                        if ($postModel->save()) {
                            $postModel->id = Helpers::createShortCode($postModel->ik);
                            $postModel->save();

                            $thumbnailLarge = $this->config->application->thumbnailDir . $postModel->id . '-' . $postModel->ik . '.big.png';
                            $thumbnailSmall = $this->config->application->thumbnailDir . $postModel->id . '-' . $postModel->ik . '.small.png';

                            if (!file_exists($permanentFile)) {
                                echo 'File ' . $permanentFile . ' Doesn\'t Exist!';
                            } else {
                                list($width, $height, $type, $attr) = @getimagesize($permanentFile);

                                // Create small and large thumbnails
                                Helpers::createThumbnail($permanentFile, $thumbnailLarge, ($width >= 560) ? 560 : $width);
                                Helpers::createThumbnail($permanentFile, $thumbnailSmall, ($width >= 244) ? 244 : $width);
                            }
                        }
                    } else echo 'Category ' . $category . ' doesn\'t exist.<br />';
                } else echo 'File ' . $permanentFile . ' doesn\'t exist.<br />';
            }

            $i++;
        }*/
    }
}


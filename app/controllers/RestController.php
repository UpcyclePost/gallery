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
    }

    public function importAction() {
        $str = 'basketball & misc junk yard fountain';
        echo preg_replace('/[^a-zA-Z0-9 ]/', '-', $str);
        die();
        $this->view->disable();

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


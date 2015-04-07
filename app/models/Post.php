<?php

class Post extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $ik;

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var integer
     */
    public $user_ik;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $category_ik;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $indexed;

    /**
     *
     * @var string
     */
    public $visible;

    /**
     *
     * @var string
     */
    public $tags;

    /**
     *
     * @var integer
     */
    public $views;

    /**
     *
     * @var integer
     */
    public $likes;

    /**
     *
     * @var integer
     */
    public $comments;

    /**
     *
     * @var integer
     */
    public $shares;

    /**
     *
     * @var integer
     */
    public $reports;

    public static $savedGalleryViews = false;

    public function initialize() {
        $this->hasOne('user_ik', 'User', 'ik');
        $this->hasOne('category_ik', 'Category', 'ik');
        $this->hasOne('ik', 'Market', 'post_ik');

        $this->useDynamicUpdate(true);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ik' => 'ik', 
            'id' => 'id', 
            'created' => 'created', 
            'user_ik' => 'user_ik', 
            'type' => 'type', 
            'status' => 'status', 
            'category_ik' => 'category_ik', 
            'title' => 'title', 
            'description' => 'description', 
            'indexed' => 'indexed', 
            'visible' => 'visible', 
            'tags' => 'tags', 
            'views' => 'views', 
            'likes' => 'likes', 
            'comments' => 'comments',
            'shares' => 'shares',
            'reports' => 'reports'
        );
    }

	public function getImageName($size)
	{
		return sprintf('%s-%s.%s.png', $this->id, $this->ik, $size);
	}

    public function thumbnail($size) {
        $url = Phalcon\DI::getDefault()->get('imageUrl');
        return $url->get('post/') . $this->id . '-' . $this->ik . '.' . $size . '.png';
    }

    public function url() {
        $url = Phalcon\DI::getDefault()->get('url');
        return $url->get('gallery/') . Helpers::url($this->Category->title) . '/' . Helpers::url($this->title) . '-' . $this->ik;
    }

    public function influence() {
        $influence = 0;
        $influence += $this->views;
        $influence += ($this->likes * 2);
        $influence += ($this->shares * 3);
        $influence += ($this->reports * -3);

        $baseScore = log(max($influence, 1));

        //Have posts gradually drop off based on age
        $now = date_create();
        $created = date_create($this->created);

        $interval = $created->diff($now);
        $weekDiff = floor(($interval->format('%d') / 7));

        if ($weekDiff > 1) {
            $weekDiff--;
            $influence = $baseScore * exp(-3 * $weekDiff * $weekDiff);
        }

        return $influence;
    }

    public function next() {
        $post = Post::findFirst(["ik > ?0 AND category_ik is not null AND status = 'posted' AND visible = 1", 'bind' => [$this->ik], 'order' => 'ik asc']);
        if (!$post) {
            $post = Post::findFirst(["ik <> ?0 AND category_ik is not null AND status = 'posted' AND visible = 1", 'bind' => [$this->ik], 'order' => 'ik asc']);
        }

        return $post->url();
    }

    public function previous() {
        $post = Post::findFirst(["ik < ?0 AND category_ik is not null AND status = 'posted' AND visible = 1", 'bind' => [$this->ik], 'order' => 'ik desc']);
        if (!$post) {
            $post = Post::findFirst(["ik <> ?0 AND category_ik is not null AND status = 'posted' AND visible = 1", 'bind' => [$this->ik], 'order' => 'ik desc']);
        }

        return $post->url();
    }

    public function index() {
        $solr = Phalcon\DI::getDefault()->get('solr');
        $update = $solr->createUpdate();

        $document = $update->createDocument();
        $document->ik = $this->ik;
        $document->id = $this->id;
        $document->category = $this->category_ik;
        $document->categoryTitle = $this->Category->title;
        $document->user = $this->user_ik;
        $document->userName = $this->User->user_name;
        $document->tags = explode(',', $this->tags);
        $document->title = $this->title;
        $document->description = $this->description;
        $document->visible = $this->visible;
        $document->views = $this->views;
        $document->likes = $this->likes;
        $document->comments = $this->comments;

        $update->addDocuments([$document]);
        $update->addCommit();

        return $solr->update($update);
    }

    public static function searchIndex($start = 0, $limit = 0, $category_ik = false, $user_ik = false, $term = false, $not = false, $sort = ['score' => 'desc', 'influence' => 'desc']) {
        $solr = Phalcon\DI::getDefault()->get('solr');

        $query = $solr->createSelect();
        $query->setQuery('visible:1');
        if ($category_ik !== false) {
            $query->createFilterQuery('category')->setQuery('category:' . $category_ik);
        }

        if ($user_ik !== false) {
            $query->createFilterQuery('user')->setQuery('user:' . $user_ik);
        }

        if ($not !== false) {
            $query->setQuery('!ik:%P1%', [$not]);
        }

        if ($term !== false) {
            $query->setQuery('search:%P1%', [$term]);
        }

        $query->setSorts($sort);

        $query->setStart($start)->setRows($limit);

        $result = $solr->execute($query);

        if (!Helpers::UAIsBot() && count($result) > 0) {
            $updates = [];
            foreach ($result AS $post) {
                $updates[] = $post['ik'];
            }
            $mm = Phalcon\DI::getDefault()->get('modelsManager');
            $mm->executeQuery("UPDATE Post SET indexed = 0, views = views + 1 WHERE ik IN(".implode(',', $updates).")");
        }

        return $result;
    }

    public static function moreLikeThis($start = 0, $limit = 0, $post_ik) {
        $solr = Phalcon\DI::getDefault()->get('solr');

        $query = $solr->createSelect();

        $query->setQuery('ik:' . $post_ik)
            ->getMoreLikeThis()
            ->setCount(20)
            ->setFields('categoryTitle,search,user')
            ->setMinimumDocumentFrequency(1)
            ->setMinimumTermFrequency(1);

        $query->setSorts(['score' => 'desc', 'influence' => 'desc']);

        $query->setStart($start)->setRows($limit);

        $resultset = $solr->select($query);
        $mlt = $resultset->getMoreLikeThis();

        $result = $mlt->getResult($post_ik);

        return $result;
    }

    public static function lookupIndex($ik) {
        $solr = Phalcon\DI::getDefault()->get('solr');

        $query = $solr->createSelect();
        $query->setQuery('visible:1');
        $query->setQuery('ik:' . $ik);

        $result = $solr->execute($query);

        return $result;
    }
}
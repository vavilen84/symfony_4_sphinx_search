<?php

namespace App\Service;

use App\Service\SphinxClient as SphinxClient;

class SphinxSearchService
{
    /**
     * @var SphinxClient $sphinx
     */
    private $sphinx;
    private $host = 'sphinxsearch';
    private $port = 9312;
    private $indexes = ['post'];

    public function __construct()
    {
//        $this->postRepository = $postRepository;
        $this->sphinx = new SphinxClient();
        $this->sphinx->setServer($this->host, $this->port);
    }

    public function getList($search)
    {
        $result = $this->search($search, $this->indexes);
        dump($result);
        if (!empty($result['total']) && !empty($result['matches'])) {
//            $queryBuilder = $this->someMethodToGetQueryBuilderFromEntitiesIds(array_keys($result['matches'])); // example method
//
//            return $this->paginator->paginate($queryBuilder, $page, Blog::ITEMS_PER_PAGE);
        }

//        return $this->paginator->paginate([], $page, Blog::ITEMS_PER_PAGE);
    }

    public function search($query, array $indexes)
    {
        $results = $this->sphinx->query($query, implode(' ', $indexes));
        if ($results['status'] !== SEARCHD_OK) {
            $error = $this->sphinx->getLastError();

            throw new \Exception($error);
        }

        return $results;
    }
}

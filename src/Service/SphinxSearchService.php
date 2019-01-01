<?php

namespace App\Service;

use App\Service\SphinxClient;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;

class SphinxSearchService
{
    /**
     * @var SphinxClient $sphinx
     */
    private $sphinx;
    private $host = 'sphinxsearch';
    private $port = 9312;
    private $indexes = ['post'];

    public function __construct(EntityManagerInterface $em)
    {
        $this->postRepository = $em->getRepository(Post::class);
        $this->sphinx = new SphinxClient();
        $this->sphinx->setServer($this->host, $this->port);
    }

    public function getList(string $search): array
    {
        $result = [];
        $searchResult = $this->search($search);
        if (!empty($searchResult['total']) && !empty($searchResult['matches'])) {
            $result = $this->postRepository->findBy(['id' => array_keys($searchResult['matches'])]);
        }

        return $result;
    }

    public function search(string $query)
    {
        $results = $this->sphinx->query($query, implode(' ', $this->indexes));
        if ($results['status'] !== SEARCHD_OK) {
            $error = $this->sphinx->getLastError();

            throw new \Exception($error);
        }

        return $results;
    }
}

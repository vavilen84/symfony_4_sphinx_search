<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadPosts($manager);
        $manager->flush();
    }

    protected function loadPosts(ObjectManager $manager)
    {
        foreach ($this->getPostData() as $data) {
            $entity = $this->getPrefilledPostEntity($data);
            $manager->persist($entity);
        }
    }

    protected function getPrefilledPostEntity(array $data): Post
    {
        $entity = new Post();
        $entity->setTitle($data['title']);
        $entity->setContent($data['content']);
        $entity->setStatus($data['status']);
        $entity->setTags($data['tags']);

        return $entity;
    }

    protected function getPostData(): array
    {
        $data = [
            [
                'title' => 'Post #1',
                'content' => 'one common',
                'status' => Post::STATUS_ACTIVE,
                'tags' => ['tag1', 'commonTag'],
            ],
            [
                'title' => 'Post #2',
                'content' => 'two common',
                'status' => Post::STATUS_ACTIVE,
                'tags' => ['tag2', 'commonTag'],
            ],
            [
                'title' => 'Post #3 INACTIVE',
                'content' => 'three common',
                'status' => Post::STATUS_ACTIVE,
                'tags' => ['tag3', 'commonTag'],
            ],
        ];

        return $data;
    }

}

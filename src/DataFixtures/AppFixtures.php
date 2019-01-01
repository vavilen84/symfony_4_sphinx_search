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

        return $entity;
    }

    protected function getPostData(): array
    {
        $data = [
            [
                'title' => 'post_1',
                'content' => 'one common',
                'status' => Post::STATUS_ACTIVE
            ],
            [
                'title' => 'post_2',
                'content' => 'two common',
                'status' => Post::STATUS_ACTIVE
            ],
            [
                'title' => 'post_3 (inactive)',
                'content' => 'three common',
                'status' => Post::STATUS_DELETED
            ],
        ];

        return $data;
    }

}

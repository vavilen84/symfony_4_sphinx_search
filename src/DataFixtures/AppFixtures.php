<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const FIRST_POST_CONTENT = 'one';
    const SECOND_POST_CONTENT = 'two';
    const THIRD_POST_CONTENT = 'three';
    const COMMON_POST_CONTENT = 'common';

    const POSTS = [
        1 => [
            'title' => 'post_1',
            'content' => self::FIRST_POST_CONTENT . ' ' . self::COMMON_POST_CONTENT,
            'status' => Post::STATUS_ACTIVE
        ],
        2 => [
            'title' => 'post_2',
            'content' => self::SECOND_POST_CONTENT . ' ' . self::COMMON_POST_CONTENT,
            'status' => Post::STATUS_ACTIVE
        ],
        3 => [
            'title' => 'post_3 (inactive)',
            'content' => self::THIRD_POST_CONTENT . ' ' . self::COMMON_POST_CONTENT,
            'status' => Post::STATUS_DELETED
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadPosts($manager);
        $manager->flush();
    }

    protected function loadPosts(ObjectManager $manager)
    {
        foreach (self::POSTS as $data) {
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
}

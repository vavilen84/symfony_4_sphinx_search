<?php

namespace Tests\Functional;

use \FunctionalTester;
use App\Tests\Functional\BaseFunctionalCest;
use App\DataFixtures\AppFixtures;

class SearchCest extends BaseFunctionalCest
{
    public function run(FunctionalTester $I)
    {
        $I->wantTo('Find post');
        $I->amOnPage('/');
        $this->seeAllPosts($I);
        $this->searchByTitle($I);
        $this->searchByContent($I);
    }

    /**
     * by default (without submited search form) we see all posts (including INACTIVE) - search doesnt take part in this post query
     *
     * @param FunctionalTester $I
     */
    protected function seeAllPosts(FunctionalTester $I)
    {
        $I->see(AppFixtures::POSTS[1]['title']);
        $I->see(AppFixtures::POSTS[2]['title']);
        $I->see(AppFixtures::POSTS[3]['title']);
    }

    protected function searchByTitle(FunctionalTester $I)
    {
        $this->submitSearchQuery(AppFixtures::POSTS[1]['title'], $I);

        $I->see(AppFixtures::POSTS[1]['title']);
        $I->dontSee(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive

        $this->submitSearchQuery(AppFixtures::POSTS[2]['title'], $I);
        $I->dontSee(AppFixtures::POSTS[1]['title']);
        $I->see(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive
    }

    protected function searchByContent(FunctionalTester $I)
    {
        $this->submitSearchQuery(AppFixtures::FIRST_POST_CONTENT, $I);
        $I->see(AppFixtures::POSTS[1]['title']);
        $I->dontSee(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive

        $this->submitSearchQuery(AppFixtures::SECOND_POST_CONTENT, $I);
        $I->dontSee(AppFixtures::POSTS[1]['title']);
        $I->see(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive

        $this->submitSearchQuery(AppFixtures::THIRD_POST_CONTENT, $I);
        $I->dontSee(AppFixtures::POSTS[1]['title']);
        $I->dontSee(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive

        $this->submitSearchQuery(AppFixtures::COMMON_POST_CONTENT, $I);
        $I->see(AppFixtures::POSTS[1]['title']);
        $I->see(AppFixtures::POSTS[2]['title']);
        $I->dontSee(AppFixtures::POSTS[3]['title']); //inactive
    }

    protected function submitSearchQuery(string $query, FunctionalTester $I)
    {
        $I->fillField('search_form[query]', $query);
        $I->click('Submit');
    }
}

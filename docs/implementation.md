# Symfony 4.* + Sphinxsearch 3.11 integration

Application is based on [this](https://github.com/vavilen84/symfony_4_basic_skeleton) skeleton but you can use yours instead.

## Entity

We will have one entity - Post. Generate entity with a command:
```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console make:entity
```
Result
```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;
    
    ... (getters and setters)

```

### Controller

Generate controller with a command:
```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console make:controller
```

Result
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SphinxSearchService;
use App\Form\SearchFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SphinxSearchService $sphinxSearchService, Request $request)
    {
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $formData = $searchForm->getData();
            $searchQuery = $formData['query'] ?? '';
            $posts = $sphinxSearchService->getList($searchQuery);
        } else {
            $postRepository = $this->getDoctrine()->getRepository(Post::class);
            $posts = $postRepository->findAll();
        }

        return $this->render('site/index.html.twig', [
            'posts' => $posts,
            'searchForm' => $searchForm->createView()
        ]);
    }
}

```

Here we have:
- By default - search is not used
- Search is used after Searchform is submited and is valid

Lets make form

## Search Form

```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console make:form
```

Result 
```php
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'trim' => true,
                'required' => true
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

```

## Sphinx

### Sphinx client

Download latest release from here [link](http://sphinxsearch.com/downloads/current/)<br>
Downloaded files includes official client - We just add a namespace to it (see src/Services/SphinxClient.php)

### Sphinx service

```php
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

```

## Twig templates

base template
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{% block title %}Welcome!{% endblock %}</title>
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <style>
        .mt10 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{% block pagetitle %}{% endblock %}</h1>
    <div>
        {% block content %}{% endblock %}
    </div>
</div>
</body>
</html>

```

action view template
```html
{% extends 'base.html.twig' %}

{% block pagetitle %}Index Page{% endblock %}

{% block content %}

    <div>
        {{ form_start(searchForm) }}
        <div class="row">
            <div class="col-xs-6">
                {{ form_row(searchForm.query, { 'attr': {'class': 'form-control'} }) }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 mt10">
                {{ form_row(searchForm.submit, { 'attr': {'class': 'btn btn-success fl'} }) }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 mt10">
                <a href="{{ path("index") }}" type="reset" class="btn btn-success">Reset</a>
            </div>
        </div>
        {{ form_end(searchForm) }}
    </div>

    <div class="mt10">
        {% if posts|length > 0 %}
            {% for post in posts %}
                <div class="well">
                    <h3>{{ post.title }}</h3>
                    <div>
                        {{ post.content }}
                    </div>
                </div>
            {% endfor %}
        {% else %}
            No results found
        {% endif %}
    </div>

{% endblock %}
```

Thats All!


 






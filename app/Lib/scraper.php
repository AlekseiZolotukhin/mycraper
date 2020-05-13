<?php

namespace App\Lib;

use App\Post;
use Goutte\Client as GoutteClient;
use Ausi\SlugGenerator\SlugGenerator;
use Html2Text\Html2Text;


/**
 * Class Scraper
 * @package App\Lib
 */
class Scraper
{
    protected $client;

    public $results = [];

    public $savedItems = 0;

    public $status = '';

    private $counter = 0;

    public function __construct(GoutteClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $target
     */
    public function handle($target)
    {
        try {
            $crawler = $this->client->request('GET', $target->url);

            $selectors = $this->getSelectors($target->selectors);

            $data = [];

            // if we have source in selector it mean that we have list with links and need go link by link
            if (isset($selectors['source'])) {
                $crawler->filter($selectors['source']['selector'])->each(function ($node) use ($selectors, &$data, $target) {
                    $link = $node->attr('href');
                    if ($this->counter < $target->counter && $link) {
                        $news = $this->client->request('GET', $link);
                        if ($news->filter($selectors['post']['selector'])->count() > 0) {
                            $this->fillData($data, $link, $news, $selectors);
                        }
                    }
                    $this->counter++;
                });

            } else if (isset($selectors['title'])) {
                $this->fillData($data, $target->url, $crawler, $selectors);
            }

            if ($data) {
                $this->save($data, $target);
            }

            $this->results = $data;
        } catch (\Exception $e) {
            $this->status = $e->getMessage() . ' ' . $e->getLine();
        }
    }


    /**
     * @param $data
     * @param $link
     * @param $news
     * @param $selectors
     * @return mixed
     */
    protected function fillData(&$data, $link, $news, $selectors)
    {

        $data['src_url'][] = $link;

        if ($news->filter($selectors['post']['selector'] . ' ' . $selectors['category']['selector'])->count()) {
            $data['category'][] = $news->filter($selectors['post']['selector'] . ' ' .
                $selectors['category']['selector'])->text();
        } else {
            $data['category'][] = '';
        }

        if ($news->filter($selectors['post']['selector'] . ' ' . $selectors['title']['selector'])->count()) {
            $data['title'][] = strip_tags($news->filter($selectors['post']['selector'] . ' ' .
                $selectors['title']['selector'])->html());
        } else {
            $data['title'][] = '';
        }

        if ($news->filter($selectors['post']['selector'] . ' ' . $selectors['body']['selector'])->count()) {
            if ($selectors['body']['attr']) {
                $html = strip_tags($news->filter($selectors['post']['selector'] . ' ' .
                    $selectors['body']['selector'])->html(), $selectors['body']['attr']);
            } else {
                $html = $news->filter($selectors['post']['selector'] . ' ' .
                    $selectors['body']['selector'])->html();
            }
            $data['body'][] = $html;
        } else {
            $data['body'][] = '';
        }

        return $data;
    }


    /**
     * @param $data
     * @param $target
     */
    protected function save($data, $target)
    {
        $generator = new SlugGenerator;

        foreach ($data['title'] as $k => $title) {

            $postExist = Post::where('src_url', $data['src_url'][$k])->first();

            if (!isset($postExist->post_id)) {

                $title = trim(strip_tags($title));

                $post = new Post();

                $post->user_id = $target->user_id;

                $newTitle = new Html2Text($title);
                $post->title = mb_substr($newTitle->getText(), 0, 200);
                if (!empty($post->title)) {
                    $post->slug = $generator->generate($post->title);
                }

                $post->excerpt = '';
                $post->body = '';
                if (isset($data['body'][$k])) {
                    $newExcerpt = new Html2Text($data['body'][$k]);
                    $post->excerpt = mb_substr($newExcerpt->getText(), 0, 200);
                    $post->body = $newExcerpt->getHtml();
                }

                $post->src_url = $data['src_url'][$k];

                $post->save();

                $this->savedItems++;
            }
        }
    }


    /**
     * @param $selectors
     * @return array
     */
    protected function getSelectors($selectors)
    {
        $values = explode('|', $selectors);

        $regex = '/(.*?)\[(.*)\]/m';

        $fields = [];

        foreach ($values as $value) {

            preg_match($regex, $value, $matches);

            if (isset($matches[1]) && isset($matches[2])) {

                $is_attribute = false;

                $selector = $matches[2];

                $attr = '';

                if (strpos($selector, "[") !== false && strpos($selector, "]") !== false) {

                    $is_attribute = true;

                    preg_match($regex, $matches[2], $matches_attr);

                    $selector = $matches_attr[1];

                    $attr = $matches_attr[2];
                }

                $fields[$matches[1]] = [
                        'field' => $matches[1],
                        'is_attribute' => $is_attribute,
                        'selector' => $selector,
                        'attr' => $attr
                ];
            }
        }

        return $fields;
    }
}

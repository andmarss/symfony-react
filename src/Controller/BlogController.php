<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
        [
            'id'    => 1,
            'slug'  => 'hello-world',
            'title' => 'Hello world!'
        ],
        [
            'id'    => 2,
            'slug'  => 'another-post',
            'title' => 'This is another post'
        ],
        [
            'id'    => 3,
            'slug'  => 'last-example',
            'title' => 'This is the last example'
        ]
    ];

    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5})
     * @param int $page
     * @return JsonResponse
     */
    public function list(int $page): JsonResponse
    {
        return $this->json([
            'page' => $page,
            'data' => array_map(function (array $post){
                return $this->generateUrl('blog_by_id', [
                    'id' => $post['id']
                ]);
            }, static::POSTS)
        ]);
    }

    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     */
    public function post(int $id): JsonResponse
    {
        return $this->json(static::POSTS[array_search($id, array_column(static::POSTS, 'id'))]);
    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     * @param string $slug
     * @return JsonResponse
     */
    public function postBySlug(string $slug): JsonResponse
    {
        return $this->json(static::POSTS[array_search($slug, array_column(static::POSTS, 'slug'))]);
    }
}
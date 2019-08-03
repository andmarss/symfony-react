<?php


namespace App\Controller;


use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page" = "\d+"})
     * @param string $page
     * @return JsonResponse
     */
    public function list(string $page): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $posts = $repository->findAll();

        return $this->json([
            'page' => $page,
            'data' => array_map(function (BlogPost $post){
                return $this->generateUrl('blog_by_slug', [
                    'slug' => $post->getSlug()
                ]);
            }, $posts)
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     */
    public function post(int $id): JsonResponse
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug")
     * @param string $slug
     * @return JsonResponse
     */
    public function postBySlug(string $slug): JsonResponse
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy([
                'slug' => $slug
            ])
        );
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add(Request $request)
    {
        /**
         * @var Serializer $serializer
         */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }
}
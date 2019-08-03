<?php


namespace App\Controller;


use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     *
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function post($post): JsonResponse
    {
        // it's do the same as find($id) on repository
        return $this->json(
            $post
        );
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * Параметр class в ParamConverter не обязателен, если указан класс в typehinting'е
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function postBySlug(BlogPost $post): JsonResponse
    {
        // it's do the same as findOneBy(['slug' => $slug]) on repository
        return $this->json(
            $post
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

    /**
     * @Route("/delete/{id}", name="post_delete", methods={"DELETE"})
     *
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function delete(BlogPost $post): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($post);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
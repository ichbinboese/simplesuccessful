<?php
namespace App\Controller\Api\Admin;

use App\Entity\Video;
use App\Entity\VideoLink;
use App\Repository\VideoLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api/admin/video', name:'api_admin_video_')]
class VideoApiController extends AbstractController
{
    #[Route('/list', name:'list', methods:['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $videos = $em->getRepository(Video::class)->findBy([], ['uploadedAt'=>'DESC']);
        $data = array_map(fn(Video $v)=>[
            'id'=>$v->getId(),
            'title'=>$v->getTitle(),
            'filename'=>$v->getFilename(),
            'description'=>$v->getDescription(),
            'uploadedAt'=>$v->getUploadedAt()->format(DATE_ATOM),
            'isPublic'=>$v->isPublic()
        ], $videos);

        return $this->json($data);
    }

    #[Route('/upload', name:'upload', methods:['POST'])]
    public function upload(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $file = $req->files->get('file');
        $title = $req->request->get('title', 'Untitled');

        if (!$file) {
            return $this->json(['error'=>'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos';
        $filesystem = new Filesystem();
        if (!$filesystem->exists($uploadDir)) {
            $filesystem->mkdir($uploadDir, 0755);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe = substr(preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName), 0, 120);
        $newFilename = uniqid($safe . '_') . '.' . $file->guessExtension();

        try {
            $file->move($uploadDir, $newFilename);
        } catch (FileException $e) {
            return $this->json(['error'=>'Upload failed: ' . $e->getMessage()], 500);
        }

        $video = new Video();
        $video->setTitle($title);
        $video->setFilename($newFilename);
        $video->setDescription($req->request->get('description'));
        $video->setIsPublic(true);
        $em->persist($video);
        $em->flush();

        return $this->json(['ok'=>true, 'videoId'=>$video->getId()]);
    }

    #[Route('/{id}/link', name:'create_link', methods:['POST'])]
    public function createLink(Video $video, Request $req, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $recipient = $req->request->get('email');
        $expires = $req->request->get('expires');

        $link = new VideoLink();
        $link->setVideo($video);
        if ($recipient) $link->setRecipientEmail($recipient);
        if ($expires) $link->setExpiresAt(new \DateTimeImmutable($expires));

        $em->persist($link);
        $em->flush();

        $publicUrl = $this->generateUrl('public_video_watch', ['token' => $link->getToken()], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->json(['token'=>$link->getToken(), 'url'=>$publicUrl]);
    }

    #[Route('/links', name:'links', methods:['GET'])]
    public function links(
        Request $request,
        VideoLinkRepository $repository,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $page = max(1, (int) $request->query->get('page', 1));
        $perPage = max(1, min(100, (int) $request->query->get('perPage', 20)));
        $status = $request->query->get('status', 'active');

        [$links, $total] = $repository->paginateByStatus($page, $perPage, $status);

        $data = array_map(function (VideoLink $link) use ($urlGenerator) {
            $video = $link->getVideo();
            return [
                'id' => $link->getId(),
                'token' => $link->getToken(),
                'video' => [
                    'id' => $video->getId(),
                    'title' => $video->getTitle(),
                ],
                'createdAt' => $link->getCreatedAt()->format(DATE_ATOM),
                'expiresAt' => $link->getExpiresAt()?->format(DATE_ATOM),
                'recipientEmail' => $link->getRecipientEmail(),
                'status' => $link->isValid() ? ($link->getExpiresAt() ? 'active_clicked' : 'active') : 'expired',
                'clicked' => $link->getExpiresAt() !== null,
                'shareUrl' => $urlGenerator->generate('public_video_watch', ['token' => $link->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }, $links);

        return $this->json([
            'items' => $data,
            'meta' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'status' => $status,
            ],
        ]);
    }

    #[Route('/{id}', name:'delete', methods:['DELETE'])]
    public function delete(Video $video, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos';
        $file = $uploadDir . '/' . $video->getFilename();
        if (file_exists($file)) { @unlink($file); }

        $em->remove($video);
        $em->flush();

        return $this->json(['ok'=>true]);
    }
}

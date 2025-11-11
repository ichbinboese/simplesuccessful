<?php
namespace App\Controller;

use App\Entity\VideoLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PublicVideoController extends AbstractController
{
    private string $uploadDir;
    private EntityManagerInterface $em;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $this->uploadDir = $params->get('kernel.project_dir') . '/public/uploads/videos';
        $this->em = $em;
    }

    #[Route('/v/{token}', name:'public_video_watch', methods:['GET'])]
    public function watch(Request $request, string $token): Response
    {
        $link = $this->em->getRepository(VideoLink::class)->findOneBy(['token' => $token]);
        if (!$link || !$link->isValid()) {
            throw $this->createNotFoundException('Link not found or expired.');
        }

        if ($link->startCountdown()) {
            $this->em->flush();
        }

        $video = $link->getVideo();
        $streamUrl = $this->generateUrl('public_video_stream', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $props = [
            'video' => [
                'id' => $video->getId(),
                'title' => $video->getTitle(),
                'description' => $video->getDescription(),
                'uploadedAt' => $video->getUploadedAt()?->format(DATE_ATOM),
                'filename' => $video->getFilename(),
            ],
            'token' => $token,
            'streamUrl' => $streamUrl,
            'shareUrl' => $this->generateUrl('public_video_watch', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
            'expiresAt' => $link->getExpiresAt()?->format(DATE_ATOM),
            'brand' => [
                'name' => 'DrSimple',
                'ctaLabel' => 'Zur Hauptseite',
                'ctaHref' => '/',
            ],
        ];

        return $this->render('public/watch.html.twig', [
            'props' => $props,
        ]);
    }

    #[Route('/v/stream/{token}', name:'public_video_stream', methods:['GET'])]
    public function videoStream(string $token, Request $request): Response
    {
        $link = $this->em->getRepository(VideoLink::class)->findOneBy(['token' => $token]);
        if (!$link || !$link->isValid()) {
            return new Response('Not found', 404);
        }

        if ($link->startCountdown()) {
            $this->em->flush();
        }

        $filePath = $this->uploadDir . '/' . $link->getVideo()->getFilename();
        if (!file_exists($filePath)) {
            return new Response('File not found', 404);
        }

        $size = filesize($filePath);
        $fm = fopen($filePath, 'rb');

        $start = 0;
        $end = $size - 1;
        $status = 200;
        $headers = [];

        if ($request->headers->has('Range')) {
            $range = $request->headers->get('Range'); // e.g. bytes=0-1023
            if (preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
                if ($matches[1] !== '') $start = intval($matches[1]);
                if ($matches[2] !== '') $end = intval($matches[2]);
                $status = 206;
                $headers['Content-Range'] = "bytes $start-$end/$size";
            }
        }

        $length = ($end - $start) + 1;
        $headers += [
            'Content-Type' => mime_content_type($filePath) ?: 'video/mp4',
            'Accept-Ranges' => 'bytes',
            'Content-Length' => (string)$length,
        ];

        $response = new Response(null, $status, $headers);

        // stream callback (keine Ausgabe direkt!)
        $response->setCallback(function () use ($fm, $start, $length) {
            fseek($fm, $start);
            $chunkSize = 1024 * 1024; // 1 MB
            $bytesToSend = $length;
            while ($bytesToSend > 0 && !feof($fm)) {
                $read = min($chunkSize, $bytesToSend);
                echo fread($fm, $read);
                flush();
                $bytesToSend -= $read;
            }
            fclose($fm);
        });

        return $response;
    }
}

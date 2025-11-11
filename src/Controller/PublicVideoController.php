<?php
namespace App\Controller;

use App\Entity\VideoLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $video = $link->getVideo();

        // render a simple video player page
        return $this->render('public/watch.html.twig', [
            'video' => $video,
            'token' => $token,
        ]);
    }

    #[Route('/v/stream/{token}', name:'public_video_stream', methods:['GET'])]
    public function videoStream(string $token, Request $request): Response
    {
        $link = $this->em->getRepository(VideoLink::class)->findOneBy(['token' => $token]);
        if (!$link || !$link->isValid()) {
            return new Response('Not found', 404);
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

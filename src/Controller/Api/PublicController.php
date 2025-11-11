<?php
namespace App\Controller\Api;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/public')]
class PublicController extends AbstractController
{
    #[Route('/videos', name:'api_public_videos', methods:['GET'])]
    public function videos(EntityManagerInterface $em)
    {
        $videos = $em->getRepository(Video::class)->findBy(['isPublic'=>true], ['uploadedAt'=>'DESC']);
        $data = array_map(fn(Video $v)=>[
            'id'=>$v->getId(),
            'title'=>$v->getTitle(),
            'description'=>$v->getDescription(),
            'uploadedAt'=>$v->getUploadedAt()->format(DATE_ATOM),
            'watchUrl'=>'/v/stream-public/'.$v->getId() // or handle via token links
        ], $videos);
        return $this->json($data);
    }
}

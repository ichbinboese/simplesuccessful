<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class FrontendController extends AbstractController
{
    #[Route('/', name:'onepager')]
    public function onepager(): Response
    {
        return $this->render('frontend/onepager.html.twig');
    }

    #[Route('/admin/{vueRouting}', name: 'admin_app', requirements: ['vueRouting' => '.*'], defaults: ['vueRouting' => null])]
    public function admin(): Response
    {
        // The admin Vue SPA will handle routing inside /admin
        return $this->render('admin/index.html.twig');
    }

    // Video pages
    #[Route('/video/marketing', name:'video_marketing')]
    public function videoMarketing(): Response
    {
        // Serve the SPA shell; Vue Router handles the rest
        return $this->render('frontend/onepager.html.twig');
    }

    #[Route('/video/marketing-explain', name:'video_marketing_explain')]
    public function videoMarketingExplain(): Response
    {
        return $this->render('frontend/onepager.html.twig');
    }

    #[Route('/video/product', name:'video_product')]
    public function videoProduct(): Response
    {
        return $this->render('frontend/onepager.html.twig');
    }

    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent() ?: '[]', true) ?: [];

        // Basic honeypot
        if (!empty($data['website'] ?? '')) {
            return new JsonResponse(['ok' => true], 200);
        }

        $constraint = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 200])],
            'email' => [new Assert\NotBlank(), new Assert\Email(), new Assert\Length(['max' => 255])],
            'goal' => [new Assert\NotBlank(), new Assert\Length(['max' => 5000])],
            'locale' => [new Assert\Optional([new Assert\Length(['max' => 10])])],
        ]);
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $constraint);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $v) {
                $prop = (string) $v->getPropertyPath();
                $errors[$prop] = $v->getMessage();
            }
            return new JsonResponse(['ok' => false, 'errors' => $errors], 422);
        }

        $name = trim($data['name']);
        $emailAddr = trim($data['email']);
        $goal = trim($data['goal']);
        $locale = $data['locale'] ?? $request->getLocale();

        $recipient = $this->getParameter('contact.recipient');
        $senderEmail = $this->getParameter('contact.sender_email');
        $senderName = $this->getParameter('contact.sender_name');

        // Build HTML body via Twig template
        $html = $this->renderView('emails/contact_request.html.twig', [
            'name' => $name,
            'email' => $emailAddr,
            'goal' => $goal,
            'locale' => $locale,
            'ip' => $request->getClientIp(),
            'ua' => $request->headers->get('User-Agent')
        ]);
        $text = $this->renderView('emails/contact_request.txt.twig', [
            'name' => $name,
            'email' => $emailAddr,
            'goal' => $goal,
            'locale' => $locale,
        ]);

        $subject = sprintf('[DrSimple] Erstgespräch – %s', $name);

        $message = (new Email())
            ->from(sprintf('%s <%s>', $senderName, $senderEmail))
            ->to($recipient)
            ->replyTo($emailAddr)
            ->subject($subject)
            ->text($text)
            ->html($html);

        $mailer->send($message);

        return new JsonResponse(['ok' => true]);
    }
}

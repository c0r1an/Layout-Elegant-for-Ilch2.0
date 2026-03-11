<?php

namespace Modules\Elegant\Boxes;

use Ilch\Validation;
use Modules\Contact\Mappers\Receiver as ReceiverMapper;

class ContactWidget extends BaseBox
{
    public function render()
    {
        $captchaNeeded = captchaNeeded();
        $receiverMapper = new ReceiverMapper();
        $receivers = [];

        try {
            $receivers = $receiverMapper->getReceivers() ?? [];
        } catch (\Throwable $exception) {
            $receivers = [];
        }

        $user = $this->getUser();
        $defaultName = ($user && method_exists($user, 'getName')) ? (string) $user->getName() : '';
        $defaultEmail = ($user && method_exists($user, 'getEmail')) ? (string) $user->getEmail() : '';
        $values = [
            'receiver' => '',
            'senderName' => $defaultName,
            'senderEmail' => $defaultEmail,
            'message' => '',
            'privacy' => '',
        ];
        $validation = null;
        $sent = false;
        $formId = 'elegantContactForm_' . $this->getUniqid();

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('elegantContactWidget') === '1' && $this->getRequest()->getPost('bot') === '') {
            $values = [
                'receiver' => (string) $this->getRequest()->getPost('receiver'),
                'senderName' => (string) $this->getRequest()->getPost('senderName'),
                'senderEmail' => (string) $this->getRequest()->getPost('senderEmail'),
                'message' => (string) $this->getRequest()->getPost('message'),
                'privacy' => (string) $this->getRequest()->getPost('privacy'),
            ];

            Validation::setCustomFieldAliases([
                'senderName' => 'name',
                'senderEmail' => 'email',
                'grecaptcha' => 'token',
            ]);

            $validationRules = [
                'receiver' => 'required',
                'senderName' => 'required',
                'senderEmail' => 'required|email',
                'message' => 'required',
                'privacy' => 'required',
            ];

            if ($captchaNeeded) {
                if (in_array((int) $this->getConfig()->get('captcha'), [2, 3], true)) {
                    $validationRules['token'] = 'required|grecaptcha:saveElegantContact';
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $receiver = $receiverMapper->getReceiverById((int) $values['receiver']);

                if (!$receiver) {
                    $validation->getErrorBag()->addError('receiver', $this->getTranslator()->trans('contactWidgetReceiverMissing'));
                } else {
                    $subject = $this->getLayout()->escape(
                        $this->getTranslator()->trans('contactWidgetSubject') . ' | ' . (string) $this->getConfig()->get('page_title')
                    );
                    $content = $this->getLayout()->escape($values['message']);
                    $date = new \Ilch\Date();
                    $senderMail = $values['senderEmail'];
                    $senderName = $this->getLayout()->escape($values['senderName']);
                    $layout = $_SESSION['layout'] ?? '';

                    if ($layout === $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/contact/layouts/mail/contact.php')) {
                        $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/contact/layouts/mail/contact.php');
                    } else {
                        $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/contact/layouts/mail/contact.php');
                    }

                    $messageReplace = [
                        '{subject}' => $subject,
                        '{content}' => $content,
                        '{encodedContent}' => rawurlencode($content),
                        '{sitetitle}' => (string) $this->getConfig()->get('page_title'),
                        '{date}' => $date->format('l, d. F Y', true),
                        '{senderMail}' => $senderMail,
                        '{senderName}' => $senderName,
                        '{from}' => $this->getTranslator()->trans('contactWidgetMailFrom'),
                        '{writes}' => $this->getTranslator()->trans('contactWidgetMailWrites'),
                        '{writeBackLink}' => $this->getTranslator()->trans('contactWidgetMailReplyHint'),
                        '{reply}' => $this->getTranslator()->trans('contactWidgetMailReply'),
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), (string) $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setFromName((string) $this->getConfig()->get('page_title'))
                        ->setFromEmail((string) $this->getConfig()->get('standardMail'))
                        ->setToName($receiver->getName())
                        ->setToEmail($receiver->getEmail())
                        ->setReplyTo($senderMail)
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->send();

                    $this->addMessage('contactWidgetSendSuccess');
                    $sent = true;
                    $values = [
                        'receiver' => '',
                        'senderName' => $defaultName,
                        'senderEmail' => $defaultEmail,
                        'message' => '',
                        'privacy' => '',
                    ];
                }
            }

            if ($validation && $validation->getErrorBag()->hasErrors()) {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        if ($captchaNeeded) {
            if (in_array((int) $this->getConfig()->get('captcha'), [2, 3], true)) {
                $this->getView()->set('googlecaptcha', new \Captcha\GoogleCaptcha($this->getConfig()->get('captcha_apikey'), null, (int) $this->getConfig()->get('captcha')));
            } else {
                $this->getView()->set('defaultcaptcha', new \Captcha\DefaultCaptcha());
            }
        }

        $this->getView()->setArray([
            'enabled' => true,
            'title' => $this->getTranslator()->trans('contactWidgetTitle'),
            'welcomeMessage' => (string) $this->getConfig()->get('contact_welcomeMessage'),
            'receivers' => $receivers,
            'captchaNeeded' => $captchaNeeded,
            'validation' => $validation,
            'values' => $values,
            'formId' => $formId,
            'sent' => $sent,
        ]);
    }
}

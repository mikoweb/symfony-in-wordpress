<?php

namespace App\Module\Core\Application\Form;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FormFactory
{
    private ?FormFactoryInterface $formFactory = null;

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function getFormFactory(): FormFactoryInterface
    {
        if (is_null($this->formFactory)) {
            $validator = Validation::createValidator();

            $this->formFactory = Forms::createFormFactoryBuilder()
                ->addExtension(new CoreExtension(translator: $this->translator))
                ->addExtension(new HttpFoundationExtension())
                ->addExtension(new ValidatorExtension($validator, translator: $this->translator))
                ->getFormFactory()
            ;
        }

        return $this->formFactory;
    }
}

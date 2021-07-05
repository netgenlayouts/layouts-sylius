<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use function is_string;

final class Locale extends Mapper
{
    private RepositoryInterface $localeRepository;

    public function __construct(RepositoryInterface $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => $this->getLocaleList(),
            'choice_translation_domain' => false,
            'multiple' => true,
            'expanded' => true,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getLocaleList(): array
    {
        $locales = $this->localeRepository->findAll();
        $localeList = [];

        /** @var \Sylius\Component\Locale\Model\Locale $locale */
        foreach ($locales as $locale) {
            if (!is_string($locale->getCode())) {
                continue;
            }

            $localeList[$locale->getName()] = $locale->getCode();
        }

        return $localeList;
    }
}

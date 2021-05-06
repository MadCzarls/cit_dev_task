<?php

declare(strict_types=1);

namespace App\Form\Weather;

use App\DTO\Weather\CountryCity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CountryCityType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Country(),
                    new Length(['max' => 50]),
                ],
                'label' => 'Country code',
                'help' => 'https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes',
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CountryCity::class,
        ]);
    }
}

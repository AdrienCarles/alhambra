<?php
// src/Form/UserEditType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le nouveau mot de passe'],
                'mapped' => false, // Ce champ n'est pas directement mappé sur l'entité User
                'constraints' => [
                    new Callback([
                        'callback' => function($object, ExecutionContextInterface $context, $payload) {
                            // Si le premier champ de mot de passe est rempli mais ne correspond pas au second
                            if (!empty($object['first']) && $object['first'] !== $object['second']) {
                                $context->buildViolation('Les mots de passe doivent correspondre.')
                                        ->atPath('second')
                                        ->addViolation();
                            }
                        },
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'required' => false, // Rendre ce champ facultatif
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    { 
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
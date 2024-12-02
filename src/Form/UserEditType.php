<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserEditType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class);

        // Récupérer l'utilisateur connecté
        $currentUser = $this->security->getUser();

        // Vérifier si l'utilisateur connecté est le même que l'utilisateur du formulaire
        $formUser = $options['data']; // L'entité User liée au formulaire

        // Ajouter le champ des rôles si l'utilisateur a le rôle ROLE_ADMINISTRATEUR
        if ($this->security->isGranted('ROLE_ADMINISTRATEUR')) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMINISTRATEUR',
                    'Bénévole' => 'ROLE_BENEVOLE',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);
        }

        // Ajouter le champ de modification de mot de passe uniquement pour l'utilisateur connecté
        if ($currentUser && $formUser && $currentUser->getId() === $formUser->getId()) {
            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Nouveau mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmez le nouveau mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Confirmez le nouveau mot de passe'],
                ],
                'required' => false, // Champ optionnel
                'mapped' => false,  // Pas directement lié à l'entité User
                'constraints' => [
                    new Callback([
                        'callback' => function ($object, ExecutionContextInterface $context, $payload) {
                            if (!empty($object['first']) || !empty($object['second'])) {
                                if ($object['first'] !== $object['second']) {
                                    $context->buildViolation('Les mots de passe doivent correspondre.')
                                            ->atPath('second')
                                            ->addViolation();
                                }
                                if (strlen($object['first']) < 6) {
                                    $context->buildViolation('Votre mot de passe doit contenir au moins {{ limit }} caractères.')
                                            ->setParameter('{{ limit }}', 6)
                                            ->atPath('first')
                                            ->addViolation();
                                }
                            }
                        },
                    ]),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

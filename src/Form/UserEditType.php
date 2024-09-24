<?php
// src/Form/UserEditType.php
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

    // Le constructeur accepte le service Security
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class);

        // Utilisation du service Security
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
        

            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Nouveau mot de passe', 'required' => false],
                ],
                'second_options' => [
                    'label' => 'Confirmez le nouveau mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Confirmez le nouveau mot de passe', 'required' => false],
                ],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Callback([
                        'callback' => function($object, ExecutionContextInterface $context, $payload) {
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

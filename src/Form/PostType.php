<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use function Symfony\Component\HttpFoundation\add;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


class PostType extends AbstractType
{
    public function __construct (SluggerInterface $slugger, Security $security){

        $this->slugger = $slugger;
        $this->security = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                $product = $event->getData();
                $form = $event->getForm();

                if (!$product || null === $product ->getId()) {
                    $form -> add('save', SubmitType::class,
                    ['label' => 'New Product']);
                }
            })
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('date_publication',DateTimeType::class, [
                'label' => 'date',
                'widget' => 'single_text',
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Post */
                $post = $event->getData();
                if (null !== $postTitle = $post->getTitle()) {
                    $post->setSlug($this->slugger->slug($postTitle)->lower());
                    $post->setUser($this->security);
                    //si le user est attaché au produit, utiliser le security passé en paramètre
                }
            })

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded'  => true,
                'multiple'  => true,
                'by_reference' => false
            ])

            ->add('image', FileType::class,[
                'label' => 'Image',
                'mapped' => false,
                'required'=> false,
                'constraints' => [
                    new File([
                        'maxSize' => '10024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'please upload a valid file'
                    ])
                ]
            ])

            ->add('save',SubmitType::class,
                ['label' => 'Edit Product']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

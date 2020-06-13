<?php

namespace App\Src\Controller;


use App\App;

use App\Src\Form\BlogPostForm;
use App\Src\Form\ConfigForm;
use App\Src\Form\UserForm;
use App\Src\Security\CsrfSecurity;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\ConfigEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\ConfigManager;
use App\Src\Service\Manager\UserManager;
use App\Src\Service\Upload\UploadFile;
use DateTime;
use Exception;
use Faker;


class FakerController extends BackController
{
    private $faker;
    public function __construct(App $app, $action, HttpRequest $request)
    {
        parent::__construct($app, $action, $request);
        $this->faker = Faker\Factory::create();
    }

    public function faker()
    {
        $this->addUser();
        $this->addConfig();
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        $faker = $this->faker;
        for ($i=0 ; $i<15 ; $i++){
            dump("ajout blog");
            $date = $faker->dateTimeBetween('-1 years','now');
            $dataBlogPost = [
                "contain" => $faker->sentence(mt_rand(30, 100)) ,
                "chapo" => $faker->sentence(mt_rand(5, 20)),
                "image" => 'https://picsum.photos/840/480?random='.$i,
                "title" => $faker->sentence(mt_rand(1, 7)),
                "slug" => $faker->slug,
                "date" => $date->format("Y-m-d"),
                "is_published" => mt_rand(1,2),
                'user_id'=>mt_rand(1,2)
            ];
            $blog = new BlogPostEntity($dataBlogPost);
            $idBlog = $manager->save($blog);
            dump($blog);
            // Les commentaires
            $manager = $this->manager->getEntityManager(CommentEntity::class);
            $nbComments = mt_rand(0,8);
            dump("ajout de ".$nbComments." commentaires");
            for ($j=0 ; $j<$nbComments ; $j++){
                $date = $faker->dateTimeBetween('-1 years','now');
                $dataComment = [
                    "date" =>$date->format("Y-m-d H:m"),
                    "message" => $faker->sentence(mt_rand(5, 20)),
                    "is_validate" => mt_rand(0, 1),
                    "post_blog_id" => $idBlog,
                    "name" => $faker->name
                ];
                $comment = new CommentEntity($dataComment);
                $manager->save($comment);
            }
        }

        $this->render('Front/Views/home.html.twig');
    }
    private function addUser()
    {
        dump("ajout de l'utilisateur admin");
        /**
         * @var UserManager $managerUser
         */
        $managerUser = $this->manager->getEntityManager(UserEntity::class);
        $admin = new UserEntity([
            "name"=>"admin",
            "admin@gregcodeur.fr",
            "avatar"=>'https://picsum.photos/480/480?random=1',
            "password"=>sha1('admin'),
            "description"=>' Passionné par le web,
            j\'aime partager mes découvertes avec les personnes qui ont cette même passion pour le web'
        ]);

        $managerUser->save($admin);
        $managerUser->createToken(uniqid(),1);
        $managerUser->createRole('admin',1);
        dump("ajout de l'utilisateur greg");
        $admin2 = new UserEntity([
            "name"=>"greg",
            "admin@gregcodeur.fr",
            "avatar"=>'https://picsum.photos/480/480?random=1',
            "password"=>sha1('greg'),
            "description"=>' Developpeur php/symfony en apprentissage, transmettre sont savoir et la meilleur facon d\'apprendre'
        ]);

        $managerUser->save($admin2);
        $managerUser->createRole('admin',1);
        $managerUser->createToken(uniqid(),1);

    }
    private function addConfig()
    {
        /**
         * @var ConfigManager $configManager
         */
        $configManager = $this->manager->getEntityManager(ConfigEntity::class);
        $config = new ConfigEntity([
            "name"=>"Grégory Castaing",
            "cv"=>"https://www.oc-p5.gregcodeur.fr/moncv.pdf",
            "presentation"=>"Developpeur web à l’écoute et éfficace",
            "email"=>"gregcodeur@gmail.com",
            "picture"=>"https://www.oc-p5.gregcodeur.fr/code.png"
        ]);
        $configManager->save($config);
    }


}
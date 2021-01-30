<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 25; $i++) {


            $roles = ['ROLE_USER', 'ROLE_EMPLOYE', 'ROLE_GERANT', 'ROLE_ADMIN'];
            $user = new User();
            $user->setPrenom('John' . $i);
            $user->setNom('Doe' . $i);
            $user->setCivilite(rand(0, 1));
            $user->setCp(rand(10, 99) . '000');
            $user->setVille('Ville' . $i);
            $user->setAdresse(rand(10, 99) . ' Rue de tests');
            $user->setCagnotte(mt_rand(0, 36));
            $password = $this->encoder->encodePassword($user, '123456789');
            $user->setPassword($password);
            $user->setEmail($i . 'user@domaine.fr');

            if ($i == 0) {
                $user->setRoles(['ROLE_SUPER_ADMIN']);
            } else {

                $user->setRoles([$roles[rand(0, 3)]]);
            }


            $manager->persist($user);

            // enregistrer les users dans une reférence
            $this->addReference('user_' . $i, $user);
        }


        $manager->flush();
    }
    public function getOrder()
    {
        return 2; // ordre d'appel
    }
}

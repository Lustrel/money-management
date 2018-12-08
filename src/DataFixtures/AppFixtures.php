<?php
namespace App\DataFixtures;

use App\Entity\Role as RoleEntity;
use App\Entity\User as UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->addRoles($manager);
        $this->addUsers($manager);
        $manager->flush();
    }

    private function addRoles(ObjectManager $manager)
    {
        $adminRole = (new RoleEntity())->setName('Administrador');
        $managerRole = (new RoleEntity())->setName('Gerente');
        $sellerRole = (new RoleEntity())->setName('Vendedor');

        $manager->persist($adminRole);
        $manager->persist($managerRole);
        $manager->persist($sellerRole);

        $this->addReference('admin-role', $adminRole);
    }

    private function addUsers(ObjectManager $manager)
    {
        $user = (new UserEntity())
            ->setEmail('admin@exemplo.com')
            ->setPassword('admin123')
            ->setPhone('31 998001111')
            ->setRole($this->getReference('admin-role'));

        $manager->persist($user);
    }
}
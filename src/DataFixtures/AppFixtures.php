<?php
namespace App\DataFixtures;

use App\Entity\Role as RoleEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\User as UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->addRoles($manager);
        $this->addUsers($manager);
        $this->addInstallmentPeriods($manager);
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

    private function addInstallmentPeriods(ObjectManager $manager)
    {
        $dailyPeriod = (new InstallmentPeriodEntity())->setName('Diariamente');
        $weeklyPeriod = (new InstallmentPeriodEntity())->setName('Semanalmente');
        $biweeklyPeriod = (new InstallmentPeriodEntity())->setName('Quinzenalmente');
        $monthlyPeriod = (new InstallmentPeriodEntity())->setName('Mensalmente');

        $manager->persist($dailyPeriod);
        $manager->persist($weeklyPeriod);
        $manager->persist($biweeklyPeriod);
        $manager->persist($monthlyPeriod);
    }

    private function addUsers(ObjectManager $manager)
    {
        $user = (new UserEntity())
            ->setName('Fulano Rodrigues')
            ->setEmail('admin@exemplo.com')
            ->setPassword('admin123')
            ->setPhone('31 998001111')
            ->setRole($this->getReference('admin-role'));

        $manager->persist($user);
    }
}
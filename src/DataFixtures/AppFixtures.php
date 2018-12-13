<?php
namespace App\DataFixtures;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
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
        $this->addCustomers($manager);
        $this->addInstallmentPeriods($manager);
        $this->addInstallmentStatus($manager);
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
        $this->addReference('seller-role', $sellerRole);
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
        $adminUser = (new UserEntity())
            ->setName('Fulano Rodrigues')
            ->setEmail('admin@exemplo.com')
            ->setPassword('admin123')
            ->setPhone('31 998001111')
            ->setActiveStatus('A')
            ->setRole($this->getReference('admin-role'));

        $sellerUser = (new UserEntity())
            ->setName('Herculano Souza')
            ->setEmail('herculano@exemplo.com')
            ->setPassword('admin123')
            ->setPhone('31 998011111')
            ->setActiveStatus('A')
            ->setRole($this->getReference('seller-role'));

        $this->addReference('seller-user', $sellerUser);

        $manager->persist($adminUser);
        $manager->persist($sellerUser);
    }

    private function addCustomers(ObjectManager $manager)
    {
        $user = (new CustomerEntity())
            ->setName('Otaviano Carlos')
            ->setDocumentNumber('12299933377')
            ->setEmail('marcelo@exemplo.com')
            ->setPhone('31 998000000')
            ->setUser($this->getReference('seller-user'));

        $manager->persist($user);
    }

    private function addInstallmentStatus(ObjectManager $manager)
    {
        $notPaid = (new InstallmentStatusEntity())
            ->setName('A receber');

        $tooLate = (new InstallmentStatusEntity())
            ->setName('Em atraso');

        $paid = (new InstallmentStatusEntity())
            ->setName('Pago');

        $manager->persist($notPaid);
        $manager->persist($tooLate);
        $manager->persist($paid);
    }
}
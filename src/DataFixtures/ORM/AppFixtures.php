<?php
namespace App\DataFixtures\ORM;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\Role as RoleEntity;
use App\Entity\User as UserEntity;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    private $adminRole;
    private $managerRole;
    private $sellerRole;
    private $sellerUser;

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

        $this->adminRole = $adminRole;
        $this->sellerRole = $sellerRole;
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
        $encoder = $this->container->get('security.password_encoder');

        $adminUser = new UserEntity();
        $adminUser->setName('Fulano Rodrigues');
        $adminUser->setEmail('herculano@exemplo.com');
        $adminUser->setEmail('admin@exemplo.com');
        $adminUser->setPassword($encoder->encodePassword($adminUser, 'admin'));
        $adminUser->setPhone('31 998001111');
        $adminUser->setRole($this->adminRole);

        $sellerUser = new UserEntity();
        $sellerUser->setName('Herculano Souza');
        $sellerUser->setEmail('vendedor@exemplo.com');
        $sellerUser->setPassword($encoder->encodePassword($sellerUser, 'admin'));
        $sellerUser->setPhone('31 998011111');
        $sellerUser->setRole($this->sellerRole);

        $this->sellerUser = $sellerUser;

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
            ->setUser($this->sellerUser);

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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
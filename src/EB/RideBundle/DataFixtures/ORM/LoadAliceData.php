<?php

namespace EB\RideBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use EB\UserBundle\Entity\User;

class LoadAliceData implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $om)
    {
        Fixtures::load(__DIR__.'/00-constants.yml', $om, array(
            'providers' => array($this),
        ));
        Fixtures::load(__DIR__.'/fixtures.yml', $om, array(
            'providers' => array($this),
        ));
    }

    public function generateSalt()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    public function generatePassword($plainPassword, $salt)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder(new User());

        return $encoder->encodePassword($plainPassword, $salt);
    }

    public function getRandomGender()
    {
        $genres = array(
            'M',
            'F',
        );

        return $genres[array_rand($genres)];
    }

    public function getRandomCarBrand()
    {
        $carBrands = array(
            'Audi',
            'BMW',
            'Porsche',
            'Opel',
            'Mercedes-Benz',
            'Volkswagen',
            'Renault',
            'Peugeot',
            'Citroen',
            'Skoda',
            'Ford',
            'Toyota',
            'Mazda',
            'Mitsubishi',
            'Nissan',
            'Dacia',
        );

        return $carBrands[array_rand($carBrands)];
    }

    public function getRandomRomaniaCountyCode()
    {
        $romaniaCountyCodes = array(
            'AB',
            'AG',
            'AR',
            'B',
            'BC',
            'BH',
            'BN',
            'BR',
            'BT',
            'BV',
            'BZ',
            'CJ',
            'CL',
            'CS',
            'CT',
            'CV',
            'DB',
            'DJ',
            'GJ',
            'GL',
            'GR',
            'HD',
            'HR',
            'IF',
            'IL',
            'IS',
            'MH',
            'MM',
            'MS',
            'NT',
            'OT',
            'PH',
            'SB',
            'SJ',
            'SM',
            'SV',
            'TL',
            'TM',
            'TR',
            'VL',
            'VN',
            'VS',
        );

        return $romaniaCountyCodes[array_rand($romaniaCountyCodes)];
    }
}

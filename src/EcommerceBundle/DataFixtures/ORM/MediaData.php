<?php

namespace EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EcommerceBundle\Entity\Media;

class MediaData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $media1 = new Media();
        $media1->setPath('http://www.plkdenoetique.com/wp-content/uploads/2016/04/legumes-825x465.png');
        $media1->setAlt('Légumes');
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setPath('http://storage-cube.quebecormedia.com/v1/dynamic_resize?quality=75&size=1200x1200&src=http%3A%2F%2Fstorage-cube.quebecormedia.com%2Fv1%2Fcl_prod%2Fcanadian_living%2Fbb92658b-5af7-47ad-ba3a-e1aea911e2c7%2F25-healthy-fruits-image1424807763.jpg');
        $media2->setAlt('Fruits');
        $manager->persist($media2);

        $media3 = new Media();
        $media3->setPath('http://wikibouffe.iga.net/thumbs/timthumb.php?w=320&src=http://wikibouffe.iga.net/images/uploads/recettes/gxNzQ1NT-poivron-rouge-jpg.jpeg');
        $media3->setAlt('Poivron rouge');
        $manager->persist($media3);

        $media4 = new Media();
        $media4->setPath('http://img.deco.fr/029E017005098953-c1-photo-oYToxOntzOjE6InciO2k6NjcwO30%3D-piment.jpg');
        $media4->setAlt('Piment');
        $manager->persist($media4);

        $media5 = new Media();
        $media5->setPath('http://blog.thalasseo.com/wp-content/uploads/2016/06/Tomate.jpg');
        $media5->setAlt('Tomate');
        $manager->persist($media5);

        $media6 = new Media();
        $media6->setPath('http://fruitsetlegumesdestrinites.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/i/s/istock_000014250412_extrasmall.jpg');
        $media6->setAlt('Poivron vert');
        $manager->persist($media6);

        $media7 = new Media();
        $media7->setPath('http://www.france5.fr/emissions/sites/default/files/images/2016/10/41/10173270-16586134.jpg');
        $media7->setAlt('Raisin');
        $manager->persist($media7);

        $media8 = new Media();
        $media8->setPath('http://producemadesimple.ca/wp-content/uploads/2015/01/orange-web-1024x768.jpg');
        $media8->setAlt('Orange');
        $manager->persist($media8);

        $manager->flush();

        $this->addReference('media1', $media1);
        $this->addReference('media2', $media2);
        $this->addReference('media3', $media3);
        $this->addReference('media4', $media4);
        $this->addReference('media5', $media5);
        $this->addReference('media6', $media6);
        $this->addReference('media7', $media7);
        $this->addReference('media8', $media8);
    }

    public function getOrder()
    {
        return 1;
    }

}
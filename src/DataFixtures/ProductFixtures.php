<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\MobilePhone;

class ProductFixtures extends Fixture
{   
    public const PRODUCT_REFERENCE = 'sample';

    public function load(ObjectManager $manager)
    {   
        // Generate phone objects randomly
        $bulkPhoneData = [
            'model' => ['I','II', 'III', 'IV'],
            'brand' => ['Iphone', 'Samsung', 'Huawei'],
            'color' => ['Or', 'Argent', 'Blanc', 'Noir'],
            'storage' => ['32','64', '128', '256'],
            'price' => ['600.00', '800.00', '1000.00', '1200.00'],
            'height' => ['12.33', '13.33', '14.33', '15.33'],
            'width' => ['4.11', '4.55', '5.11', '5.55'],
            'resolution' => [458, 667, 846, 912],
            'weight' => [151, 162, 173, 184],
        ];
        
        for ($i=0; $i < 30; $i++) { 
            $phone = new MobilePhone();
            //set phone model
            $model = $bulkPhoneData['model'][array_rand($bulkPhoneData['model'])];
            $phone->setModel($model);
            //set phone brand
            $brand = $bulkPhoneData['brand'][array_rand($bulkPhoneData['brand'])];
            $phone->setBrand($brand);
            //set phone color
            $color = $bulkPhoneData['color'][array_rand($bulkPhoneData['color'])];
            $phone->setColor($color);
            //set phone storage
            $storage = $bulkPhoneData['storage'][array_rand($bulkPhoneData['storage'])];
            $phone->setStorage($storage);
            //set phone price
            $price = $bulkPhoneData['price'][array_rand($bulkPhoneData['price'])];
            $phone->setPrice($price);
            //set phone height
            $height = $bulkPhoneData['height'][array_rand($bulkPhoneData['height'])];
            $phone->setHeight($height);
            //set phone width
            $width = $bulkPhoneData['width'][array_rand($bulkPhoneData['width'])];
            $phone->setWidth($width);
            //set phone resolution
            $resolution = $bulkPhoneData['resolution'][array_rand($bulkPhoneData['resolution'])];
            $phone->setScreenResolution($resolution);
            //set phone weight
            $weight = $bulkPhoneData['weight'][array_rand($bulkPhoneData['weight'])];
            $phone->setWeight($weight);

            // Persist the phone
            $manager->persist($phone);

        }

        $manager->flush();
        // make a reference to phone object to be used in other fixture file
        $this->addReference(self::PRODUCT_REFERENCE, $phone);
    }
}

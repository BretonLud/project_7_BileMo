<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $brands = ['Samsung', 'Apple', 'Nokia', 'Xiaomi', 'OPPO', 'Sony', 'LG'];
        $screenSize = [
            "720x1280",  // HD
            "1080x1920", // Full HD
            "1440x2560", // Quad HD
            "720x1520",  // HD+
            "1080x2340", // Full HD+
            "1440x2960", // Quad HD+
            "1284x2778", // iPhone 12 Pro Max
            "1170x2532", // iPhone 12
            "1125x2436", // iPhone X
            "1242x2688", // iPhone XS Max
            "1080x2400", // Full HD+ (20:9)
            "1440x3200", // Quad HD+ (20:9)
            "1536x2048", // iPad mini
            "1668x2388"
        ];
        $customerName = ['Free', 'Orange', 'Bouygues', 'SFR', 'Sosh'];
        $customers = [];
        for($i = 0; $i < 5; $i++) {
            $customer = new Customer();
            $customer->setName($customerName[$i]);
            $manager->persist($customer);
            $customers[] = $customer;
        }
        
        for($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setName($faker->word());
            $product->setPrice($faker->randomFloat(2, 10, 1000));
            $product->setDescription($faker->text());
            $product->setBrand($brands[array_rand($brands)]);
            $product->setScreenSize($screenSize[array_rand($screenSize)]);
            $product->setColor($faker->colorName());
            $manager->persist($product);
        }
        
        for($i = 0; $i < 100; $i++) {
            
            $user = new User();
            switch ($i)
            {
                case 1;
                    $user->setLastname('admin');
                    $user->setFirstname('admin');
                    $user->setEmail('admin@example.com');
                    $user->setCustomer($customers[array_rand($customers)]);
                    $user->setPassword($this->passwordHasher->hashPassword(
                        $user,
                        'admin'
                    ));
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 2;
                    $user->setLastname('client');
                    $user->setFirstname('client');
                    $user->setEmail('client@example.com');
                    $user->setCustomer($customers[array_rand($customers)]);
                    $user->setPassword($this->passwordHasher->hashPassword(
                        $user,
                        'client'
                    ));
                    $user->setRoles(['ROLE_CUSTOMER']);
                    break;
                default;
                    $user->setLastname($faker->lastName);
                    $user->setFirstname($faker->firstName);
                    $user->setEmail($faker->email);
                    $user->setCustomer($customers[array_rand($customers)]);
                    $user->setPassword($this->passwordHasher->hashPassword(
                        $user,
                        $faker->password()
                    ));
                    
                    if ($i % 5 === 0) {
                        $user->setRoles(['ROLE_CUSTOMER']);
                    }
            }
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}

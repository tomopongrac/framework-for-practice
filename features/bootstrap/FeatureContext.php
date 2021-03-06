<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    private static $container;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeSuite
     */
    public static function bootstrapApp()
    {
        $environmentEnv = 'behat';
        require __DIR__.'/../../bootstrap/app.php';

        self::$container = $container;

        $em = $container->get(\Doctrine\ORM\EntityManager::class);

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = array(
            $em->getClassMetadata('App\Models\User'),
        );
        $tool->updateSchema($classes);
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $em = self::$container->get(\Doctrine\ORM\EntityManager::class);
        $em->createQuery('DELETE FROM App\Models\User')->execute();
    }

    /**
     * @Given there is an user :arg1 with email :arg2 and password :arg3
     */
    public function thereIsAnUserWithEmailAndPassword($username, $email, $password)
    {
        $em = self::$container->get(\Doctrine\ORM\EntityManager::class);

        $user = new \App\Models\User();
        $user->update([
            'name' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['costs' => 12])
        ]);

        $em->persist($user);
        $em->flush();
    }
}

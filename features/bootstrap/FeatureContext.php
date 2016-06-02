<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext implements Context, SnippetAcceptingContext
{
    
    /** @var \Zend\ServiceManager\ServiceManager */
    protected static $serviceManager;
    
    /** @var  \Application\Entity\User */
    protected $temporaryUser;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        self::bootstrapApplication();
    }
    
    public static function bootstrapApplication()
    {
        if (is_null(static::$serviceManager)) {
            \ApplicationTest\Bootstrap::init();
            static::$serviceManager = \ApplicationTest\Bootstrap::getServiceManager();
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return static::$serviceManager->get('entityManager');
    }

    /**
     * @Given I have a stored user with:
     */
    public function iHaveAStoredUserWith(TableNode $table)
    {
        /** @var \Zend\Stdlib\Hydrator\ClassMethods $hydrator */
        $hydrator = static::$serviceManager->get('hydratorManager')->get('classMethods');
        
        $user = new \Application\Entity\User();
        
        $hydrator->hydrate($table->getRowsHash(), $user);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        $this->temporaryUser = $user;
    }

    /**
     * @AfterScenario @cleanup
     */
    public function cleanup(\Behat\Behat\Hook\Scope\AfterScenarioScope $scope)
    {
        if ($this->temporaryUser) {
            $this->getEntityManager()->remove($this->temporaryUser);
            $this->getEntityManager()->flush();
        }
    }
    
    protected function getUrl(array $parts)
    {
        $url = '';
        foreach ($parts as $part) {
            if (isset($this->{trim($part, '%')})) {
                $url .= $this->{trim($part, '%')}->getId();
            } else {
                $url .= $part;
            }
        }
        return $url;
    }

    /**
     * @Then the url should match:
     */
    public function theUrlShouldMatch(TableNode $table)
    {
        return $this->assertPageAddress($this->getUrl($table->getRow(0)));
    }

    /**
     * @When I go to:
     * @Given I am on:
     */
    public function iGoTo(TableNode $table)
    {
        return $this->visit($this->getUrl($table->getRow(0)));
    }

}

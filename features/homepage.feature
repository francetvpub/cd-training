@homepage
Feature: Homepage default behavior
  
  Scenario: The title display
    When I am on the homepage
    And I should see "Sylvia"
    And I should see "Heng"
    And I should see "Julien"
    And I should see "Elvis"
    And I should see "Xavier"
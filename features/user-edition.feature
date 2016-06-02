Feature: User edition

  @cleanup
  Scenario: Display an existing user
    Given I have a stored user with:
    | Firstname | Fred |
    | Lastname | Dewinne |
    When I go to:
    | /user/ | %temporaryUser% |
    Then the "Firstname" field should contain "Fred"
    And the "Lastname" field should contain "Dewinne"

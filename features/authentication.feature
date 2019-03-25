Feature: Authentication
  In order to gain access to the site management area
  As an admin
  I need to be able to login and logout

  Scenario: Sign in
    Given there is an user "johnDoe" with email "johnDoe@example.com" and password "john"
    And I am on "/auth/signin"
    And I fill in "email" with "johnDoe@example.com"
    And I fill in "password" with "john"
    And I press "Sign in"
    Then I should see "johnDoe"

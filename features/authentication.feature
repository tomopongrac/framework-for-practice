Feature: Authentication
  In order to gain access to the site management area
  As an admin
  I need to be able to login and logout

  Scenario: Signin
    Given I am on "/auth/signin"
    And I fill in "email" with "admin@example.com"
    And I fill in "password" with "admin"
    And I press "Sign in"

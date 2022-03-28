@gharchive
Feature: import archive

  Scenario: Import archive for an existing day
    Given I am the system
    #And I fetch data from gharchive for "2022-03-20"
    When I execute import for "2022-03-20" hour "0"
    Then I found "2022-03-20" in search





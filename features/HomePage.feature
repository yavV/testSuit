Feature: Display pages in the shop

  @homePage
  Scenario: Home Page
    When I access the main page
    Then I see the main page

  @menu
  Scenario: Menu click
    Given I access the main page
    When I can see the menu
    And I click the 1st menu entry
    And I wait for the page to be changed
    Then the current page title is not the title of the main page

  @menu
  Scenario: Sub menu shows
    Given I access the main page
    When I can see the menu
    And I can move the mouse over the 1st menu entry
    Then I can see the submenu

  @logo
  Scenario: Click the logo
    Given I access the path "/mein-schuhcenter.html"
    When I can see the logo
    And I click on the logo
    Then the current page title is the same as the title of the main page

  @images
  Scenario: All images are shown
    Given I access the main page
    Then each image in the body is displayed

  @search
  Scenario: Searching works
    Given I access the main page
    When I enter "her" in the search field in the footer
    And I wait at most 10 seconds for the search block to be visible
    And I can see the element Vorschläge
    Then the value of the element Vorschläge is not "0"

  @filials
  Scenario: Filialfinder form is shown and works
    When I access the main page
    And I can see the footer's block Filialfinder
    And I can see the input element in the block Filialfinder
    And I enter 10247 in the input element in the block Filialfinder
    And I click on the button Filialfinder
    Then I wait at most 10 seconds for the title Siemes Schuhcenter Berlin-Heinersdorf to be visible

  @footer
  Scenario: Footer is displayed
    Given I access the main page
    Then I can see the footer

  @footer
  Scenario: Footer links are displayed
    Given I access the main page
    Then each link in the footer is shown

  @footer
  Scenario: Footer links work
    Given I access the main page
    Then each link in the footer works

  @footer
  Scenario Outline: Selected links in the footer are shown and work
    When I access the main page
    And I can see the footer's element with the title "<linkName>"
    Then the "<linkName>" link in the footer works

    Examples:
      | linkName                                       |
      | Werden Sie Fan von schuhcenter.de auf Facebook |
      | Besuchen Sie schuhcenter.de auf Google+        |
      | Folgen Sie schuhcenter.de auf Twitter          |
      | FAQ & Hilfe                                    |
      | Zahlung & Versand                              |
      | Newsletter                                     |
      | Karriere bei Siemes                            |
      | Zum Unternehmen                                |
      | Allgemeine Geschäftsbedingungen                |
      | Impressum Schuhcenter                          |
      | Datenschutz                                    |
      | Widerrufsbelehrung und Widerrufsformular       |
      | Vans Schuhe                                    |
      | Bugatti Schuhe                                 |
      | Asics Schuhe                                   |
      | Gabor Schuhe                                   |
      | Tamaris Schuhe                                 |
      | Ara Schuhe                                     |
      | Rieker Schuhe                                  |
      | Alle Marken anzeigen                           |
      | Adidas Fußballschuhe                           |
      | Nike Fußballschuhe                             |
      | Damen Ballerinas                               |
      | Fußballschuhe                                  |
      | Sneaker                                        |
      | Stiefeletten für Damen                         |


  @footer
  Scenario: Bewertungen link is shown and works
    When I access the main page
    And I wait at most 15 seconds for the Bewertungen link to be visible
    And I can see the Bewertungen element
    Then the Bewertungen link in the footer works

  @footer
  Scenario: Filialen link is shown and works
    When I access the main page
    And I can see the footer's element with the title Filialen
    And I click the Filialen link
    And I wait at most 15 seconds for the Filialen block to be visible
    Then I can see the label for Filialfinder

  @footer
  Scenario: Contact link is shown and works
    Given I access the main page
    When I can see the footer's element Contact
    And I click the Contact link
    Then I can see the button that contains "Nachricht abschicken"

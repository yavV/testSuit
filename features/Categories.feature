Feature: Display categories pages

  @mainCategories
  Scenario: Main categories
    Given I access the main page
    When I can see the menu
    Then all menus work

  @categoriesItems
  Scenario: Items in categories
    Given I access the main page
    When I can see the menu
    Then there are items at all of categories pages

  @subcategories
  Scenario: Subcategories are shown
    Given I access the main page
    When I can see the menu
    Then if there are subcategories they could be opened

  @categoriesFilters
  Scenario: Subcategories are shown
    Given I access the main page
    When I can see the menu
    Then filters work on every category page
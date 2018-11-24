<?php

use Facebook\WebDriver\WebDriverBy;

/**
 * This class contains tests related to the search.
 */
class SearchContext extends BaseContext
{

    /**
     * @When I enter :value in the search field in the footer
     * @param string $value
     * @throws \Exception
     */
    public function iEnterAValueInTheSearchFieldInTheFooter($value)
    {
        $xPath = "//input[@id='searchParam_desktop']";
        $this->iEnterAValueInTheFieldWithXpath($value, $xPath);
    }

    /**
     * @When /^I wait at most ([1-9][0-9]*) seconds for the search block to be visible$/
     * @param integer $seconds
     * @throws \Exception
     */
    public function iWaitForTheSearchBlockToBeVisible($seconds)
    {
        $xpath = "//div[@id='searchKatContainer']";
        $this->iWaitForTheElementWithXpath($seconds, $xpath);
        $this->iCanTheSeeElementWithXpath($xpath);
    }

    /**
     * @Then I can see the element Vorschläge
     * @throws \Exception
     */
    public function iCanSeeTheElementVorschlage()
    {
        $xPath = "//div[@id='searchKatContainer']/div/p[contains(., 'Vorschläge')]";
        $this->iCanSeeTheElementsWithXpath($xPath);
    }

    /**
     * @Then /^the value of the element Vorschläge is not "([0-9]*)"$/
     * @param integer $value
     * @throws \Exception
     */
    public function expectDifferentValueForTheElementVorschlage($value)
    {
        $xPath = "//span[@id='onsitesearch-suggest-count']";
        $actualValue = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath))->getText();
        if ($actualValue === $value) {
            $message = 'Wrong value ('.$value.') of Vorschläge element. Actual value is ' . $actualValue;
            throw new \RuntimeException($message);
        }
    }
}

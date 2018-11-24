<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\ElementNotVisibleException;

/**
 * This class contains features related to the "Filialfinder"
 */
class FilialsContext extends BaseContext
{
    /**
     * @Then I can see the footer's block Filialfinder
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheFootersBlockFilialfinder()
    {
        $xPath = "//footer//div[@class='store_loc']";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @Then I can see the input element in the block Filialfinder
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheInputElementInTheBlockFilialfinder()
    {
        $xPath = "//footer//div[@class='store_loc']//input[@name='searchparam']";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When /^I enter ([0-9]*) in the input element in the block Filialfinder$/
     * @param integer $value
     * @throws \Exception
     */
    public function iEnterAValueInTheInputElementInTheBlockFilialfinder($value)
    {
        $xPath = "//footer//div[@class='store_loc']//input[@name='searchparam']";
        $field = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        if (!$field->isDisplayed()) {
            throw new \RuntimeException('Field not visible.');
        }
        $field->sendKeys($value);
    }

    /**
     * @Then I click on the button Filialfinder
     * @throws \Exception
     */
    public function iClickOnTheButtonFilialfinder()
    {
        $xPath = "//footer//section[@id='main_footer_block']//div[@class='store_loc']//button[@title='Filialfinder']";
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        if (!$element->isDisplayed()) {
            throw new ElementNotVisibleException('Element is not displayed');
        }
        $element->click();
    }

    /**
     * @When /^I wait at most ([1-9][0-9]*) seconds for the title Siemes Schuhcenter Berlin-Heinersdorf to be visible$/
     * @param integer $seconds
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iWaitForTheTitleSiemesSchuhcenterBerlinHeinersdorfToBeVisible($seconds)
    {
        $title = 'Siemes Schuhcenter Berlin-Heinersdorf';
        $xpath = "//div[@class='store_finder_title brdr_rt col-sm-4']/h1[text()='" . $title . "']";
        $this->iWaitForTheElementWithXpath($seconds, $xpath);
        $this->iCanSeeTheElementsWithXpath($xpath);
    }
}

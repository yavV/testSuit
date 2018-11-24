<?php

use Facebook\WebDriver\WebDriverBy;

/**
 * This class contains tests related to the footer.
 */
class FooterContext extends BaseContext
{

    /**
     * @Then I can see the footer
     * @throws \Exception
     */
    public function iCanSeeTheFooter()
    {
        $xPath = '//footer';
        $this->iCanSeeTheElementsWithXpath($xPath);
    }

    /**
     * @Then each link in the footer is shown
     * @throws \Exception
     */
    public function eachLinkInTheFooterIsShown()
    {
        $this->allLinksInElementAreShown('//footer');
    }

    /**
     * @Then each link in the footer works
     * @throws \Exception
     */
    public function allLinksInTheFooterWorks()
    {
        $this->allLinksInElementWork('//footer');
    }

    /**
     * @Then the ":title" link in the footer works
     * @param string $title
     * @throws \RuntimeException
     */
    public function theLinkInTheFooterWorks($title)
    {
        $xPath = "//footer//a[@title='$title']";
        $this->theLinkInTheElementWithXpathWorks($xPath);
    }

    /**
     * @Then I can see the footer's element with the title ":title"
     * @param string $title
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheFootersElementWithTheTitle($title)
    {
        $xPath = "//footer//a[@title='$title']";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @Then the Trusted shop link works in the footer
     * @throws \RuntimeException
     */
    public function theTrustedShopLinkWorksInTheFooter()
    {
        $xPath = "//footer//img[@alt='icon_trusted_shop']/parent::a";
        $this->theLinkInTheElementWithXpathWorks($xPath);
    }

    /**
     * @Then I can see the footer's element with the title "Trusted shop"
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheFootersElementWithTitleTrustedShop()
    {
        $xPath = "//footer//img[@alt='icon_trusted_shop']/parent::a";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When /^I wait at most ([1-9][0-9]*) seconds for the Filialen block to be visible$/
     * @param integer $seconds
     * @throws \Exception
     */
    public function iWaitForTheFilialenBlockToBeVisible($seconds)
    {
        $xpath = "//div[@class='store_finder_title']/h1[contains(text(),'Filialfinder')]";
        $this->iWaitForTheElementWithXpath($seconds, $xpath);
        $this->iCanSeeTheElementsWithXpath($xpath);
    }

    /**
     * @Then I can see the footer's element with the title Filialen
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheFootersElementWithTheTitleFilialen()
    {
        $xPath = "//footer//a[@title='Unsere Filialen']";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When I click the Filialen link
     */
    public function iClickTheFilialenLink()
    {
        $xPath = "//footer//a[@title='Unsere Filialen']";
        $filialenLink = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        $filialenLink->click();
    }

    /**
     * @Then I can see the label for Filialfinder
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheLabelForTheFilialfinder()
    {
        $xPath = "//div[@class='store_finder_title']/h1[contains(text(),'Filialfinder')]";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When /^I wait at most ([1-9][0-9]*) seconds for the button that contains "Nachricht abschicken" to be visible$/
     * @param integer $seconds
     * @throws \Exception
     */
    public function iWaitForTheButtonThatContainsNachrichtAbschickenToBeVisible($seconds)
    {
        $xpath = "//button[@type='submit' and contains(text(),'Nachricht abschicken')]";
        $this->iWaitForTheElementWithXpath($seconds, $xpath);
        $this->iCanSeeTheElementsWithXpath($xpath);
    }

    /**
     * @Then I can see the footer's element Contact
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheFootersElementContact()
    {
        $xPath = "//footer//a[@title='Kontaktmöglichkeiten']";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When I click the Contact link
     */
    public function iClickTheContactLink()
    {
        $xPath = "//footer//a[@title='Kontaktmöglichkeiten']";
        $filialenLink = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        $filialenLink->click();
    }

    /**
     * @Then I can see the button that contains "Nachricht abschicken"
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheButtonThatContainsNachrichtAbschicken()
    {
        $xpath = "//button[@type='submit' and contains(text(),'Nachricht abschicken')]";
        $this->iWaitForTheElementWithXpath(15, $xpath);
        $this->iCanSeeTheElementsWithXpath($xpath);
    }

    /**
     * @When /^I wait at most ([1-9][0-9]*) seconds for the Bewertungen link to be visible$/
     * @param integer $seconds
     * @throws \Exception
     */
    public function iWaitForTheBewertungenLinkToBeVisible($seconds)
    {
        $xpath = "//footer//a[contains(text(),'Bewertungen')]";
        $this->iWaitForTheElementWithXpath($seconds, $xpath);
        $this->iCanSeeTheElementsWithXpath($xpath);
    }

    /**
     * @Then I can see the Bewertungen element
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheBewertungenElement()
    {
        $xPath = "//footer//a[contains(text(),'Bewertungen')]";
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @Then the Bewertungen link in the footer works
     * @throws \RuntimeException
     */
    public function theBewertungenLinkInTheFooterWorks()
    {
        $xPath = "//footer//a[contains(text(),'Bewertungen')]";
        $this->theLinkInTheElementWithXpathWorks($xPath);
    }
}

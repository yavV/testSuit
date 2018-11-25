<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\ElementNotVisibleException;

/**
 * This class contains tests related to the main page.
 */
class HomePageContext extends BaseContext
{
    /**
     * @var RemoteWebElement[]
     */
    private $menuElements;

    /**
     * @var string
     */
    private $mainPageTitle;

    /**
     * @var string
     */
    private $pageTitle;

    /**
     * @When I can see the submenu
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheSubmenu()
    {
        $xPath = "//div[contains(@class,'submenutoggle1')]";
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        $this->getWebDriver()->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOf($element));
        $this->iCanTheSeeElementWithXpath($xPath);
    }

    /**
     * @When I can see the logo
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Facebook\WebDriver\Exception\ElementNotVisibleException
     */
    public function iCanSeeTheLogo()
    {
        $this->iCanTheSeeElementWithXpath("//a[@class='logo_pos_block']");
    }

    /**
     * @When /^I click the ([1-9][0-9]*)st|nd|rd|th menu entry$/
     * @param integer $menuEntry
     */
    public function iClickTheMenuEntry($menuEntry)
    {
        $this->mainPageTitle = $this->getWebDriver()->getTitle();
        $xPath = "//*[@id=\"top_menu\"]/a";
        $menuElements = $this->getWebDriver()->findElements(WebDriverBy::xpath($xPath));
        $this->iClickElement($menuElements[$menuEntry - 1]);
    }

    /**
     * @When /^I can move the mouse over the ([1-9][0-9]*)st|nd|rd|th menu entry$/
     * @param integer $menuEntry
     */
    public function iCanMoveMouseOverTheMenuEntry($menuEntry)
    {
        $xPath = "/*[@id=\"top_menu\"]/a";
        $this->menuElements = $this->getWebDriver()->findElements(WebDriverBy::xpath($xPath));
        $this->moveMouseOverElement($this->menuElements[$menuEntry - 1]);
    }

    /**
     * @Then I click on the logo
     */
    public function iClickOnTheLogo()
    {
        $this->pageTitle = $this->getWebDriver()->getTitle();
        $this->getWebDriver()->findElement(WebDriverBy::xpath("//a[@class='logo_pos_block']"))->click();
    }

    /**
     * @Then I see the main page
     */
    public function iSeeTheMainPage()
    {
        $this->getWebDriver()->findElement(WebDriverBy::xpath("//a[@class='logo']"));
    }

    /**
     * @Then the current page title is not the title of the main page
     * @throws \RuntimeException
     */
    public function theCurrentPageTitleIsNotTheTitleOfTheMainPage()
    {
        if ($this->getWebDriver()->getTitle() === $this->mainPageTitle) {
            throw new \RuntimeException('Home page opened');
        }
    }

    /**
     * @Then the current page title is the same as the title of the main page
     * @throws \RuntimeException
     */
    public function theCurrentPageTitleIsTheSameAsTheTitleOfTheMainPage()
    {
        if ($this->getWebDriver()->getTitle() === $this->pageTitle) {
            throw new \RuntimeException('The same page opened');
        }
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
            throw new ElementNotVisibleException('The Filialfinder button is not displayed.');
        }
        $element->click();
    }
}

<?php

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Created by PhpStorm.
 * User: yav
 * Date: 05.09.17
 * Time: 16:04
 */
trait ContextTrait
{

    /**
     * @When /^I access the path "([^"]+)"$/
     */
    public function iAccessThePath($path)
    {
        $this->getWebDriver()->get(getenv(self::BASE_URL) . $path);

        $_wd = $this->getWebDriver();
        $this->getWebDriver()->wait(1,500)->until(
            function () use ($_wd){
               return $_wd->executeScript("return document.readyState") == "complete";
            }
        );

        if ($path === '/' && $this->siteTitle === '') {
            $this->siteTitle = $this->getWebDriver()->getTitle();
        }
    }

    /**
     * @Then I can see element by xpath :xpath
     */
    public function iCanSeeElementByXpath($xpath)
    {

        /**
         * @var $element RemoteWebElement
         */
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xpath));
        //echo $element->getAttribute('outerHTML');

        $this->getWebDriver()->wait(10,1000)->until(
            WebDriverExpectedCondition::visibilityOf($element)
        );


        if (!$element->isDisplayed()) {
            throw new \Exception("Element not visible.");
        }
        //$this->getElementByIdJS();
    }

    /**
     * @Then I can see elements by xpath :xpath
     */
    public function iCanSeeElementsByXpath($xpath)
    {
        $elements = $this->getWebDriver()->findElements(WebDriverBy::xpath($xpath));



        foreach ($elements as $element) {
            if (!$element->isDisplayed()) {
                throw new \Exception("Element not visible.");
            }
        }
    }

    /**
     * @Then I can click on element by xpath :xpathBase
     */
    public function iCanClickOnElementByXpath($xpath)
    {
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xpath));
        if (!$element->isDisplayed()) {
            throw new \Exception("Element is not displayed");
        }
        $element->click();
    }

    /**
     * @When I wait for :seconds seconds
     */
    public function iWaitForSeconds($seconds)
    {
        $this->getWebDriver()->wait($seconds);
    }

    /**
     * @When I enter :value in field  with xpath :xpath
     */
    public function iEnterInFieldWithXpath($value, $xpath)
    {

        $field = $this->getWebDriver()->findElement(WebDriverBy::xpath($xpath));
        if (!$field->isDisplayed()) {
            $this->getWebDriver()->takeScreenshot('./screens/field' . $xpath . '.png');
            throw new \Exception("Field not visible.");
        }
        $field->sendKeys($value);
    }

    /**
     * @Then Value of xpath element :xpath is not :value
     */
    public function valueOfXpathElementIsNot($value, $xpath)
    {
        $searchResults = $this->getWebDriver()->findElement(WebDriverBy::xpath($xpath))->getText();
        if ($searchResults === $value) {
            throw new \Exception('Wrong value in field');
        }
    }

    /**
     * @Then Value of xpath element :xpath is :value
     */
    public function valueOfXpathElementIs($value, $xpath)
    {
        $searchResults = $this->getWebDriver()->findElement(WebDriverBy::xpath($xpath))->getText();
        if ($searchResults != $value) {
            throw new \Exception('Wrong value in field');
        }
    }

    /**
     * @Then I can click element :xpath
     */
    public function iCanClickElementByXpath($xpath)
    {
        $element = $this->getWebDriver()
            ->findElements(WebDriverBy::xpath($xpath));
        $element->click();

    }

    /**
     * @Then I can click element :element
     */
    public function iCanClickElement($element)
    {
        $element->click();
    }

    /**
     * @Then click on element xpath :xpathBase and see element by xpath :xpathDesired
     */
    public function clickOnElementXpathAndSeeElementByXpath2($xpathBase, $xpathDesired)
    {

        $this->getWebDriver()->findElement(WebDriverBy::xpath($xpathBase))->click();
        $this->getWebDriver()->wait(5);
        $this->getWebDriver()->findElement(WebDriverBy::xpath($xpathDesired));
    }
    /**
     * @When I wait max :seconds seconds for element by xpath :xpath
     */
    public function iWaitMaxSecondsForElementByXpath($seconds,$xpath)
    {

        $this->getWebDriver()->wait($seconds,1000)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($xpath))
        );
    }

    /**
     * @param RemoteWebElement $element
     */
    public function moveMouseOverElement(RemoteWebElement $element)
    {

        //Next line is preferred method, but while there is a bug in gekkoDriver we should use javscript
        //$this->getWebDriver()->action()->moveToElement($this->menuElements[$position])->perform();

        $script = "var fireEvent = arguments[0];" .
            "var evObj = document.createEvent('MouseEvents');" .
            "evObj.initEvent( 'mouseover', true, true );" .
            "fireEvent.dispatchEvent(evObj);";

        $this->getWebDriver()->executeScript($script, [$element]); //
    }

    public function curlCheckLinkState($url)
    {

        $curlInit = curl_init($url);

        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit, CURLOPT_FOLLOWLOCATION, 0);

        $response = curl_exec($curlInit);

        curl_close($curlInit);

        return $response;
    }

    public function getElementByIdJS(){
        /**
         * @var $webElement RemoteWebElement
         */
        $script = "document.evaluate(\"//footer//a[contains(text(),'Bewertungen')]\", document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null );";
        $webElement =         $this->getWebDriver()->executeScript($script, []);

    }




}
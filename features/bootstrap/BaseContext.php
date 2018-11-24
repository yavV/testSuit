<?php

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\ElementNotVisibleException;
use YavV\BehatRunner\BasicBehatContext;

/**
 * This class contains functions useful for writing tests
 */
class BaseContext extends BasicBehatContext
{
    /**
     * @When I can see the menu
     * @throws ElementNotVisibleException
     */
    public function iCanSeeTheMenu()
    {
        $xPath = "//div[@id='mainnavigation']/div[@class='container']/ul[@class='nav nav-pills unstyled']";
        $this->menu = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        if (!$this->menu->isDisplayed()) {
            throw new ElementNotVisibleException('Menu not visible.');
        }
    }

    /**
     * @Given I access the main page
     * @throws \Exception
     */
    public function iAccessTheMainPage()
    {
        $this->iAccessThePath('/');
    }

    /**
     * @Given I access the path :path
     * @param string $path
     * @throws \Exception
     */
    public function iAccessThePath($path)
    {
        $this->getWebDriver()->get(getenv(self::BASE_URL) . $path);
        $this->iWaitForThePageToBeLoaded();
    }

    /**
     * @throws \Exception
     */
    public function iWaitForThePageToBeLoaded()
    {
        $webDriver = $this->getWebDriver();
        $webDriver->wait(10, 500)->until(
            /** @return bool */
            function () use ($webDriver) {
                return $webDriver->executeScript('return document.readyState') === 'complete';
            }
        );
    }

    /**
     * @When I wait for the page to be changed
     * @throws \Exception
     */
    public function iWaitForThePageToBeChanged()
    {
        $oldPage = $this->getWebDriver()->findElement(WebDriverBy::tagName('html'));
        $webDriver = $this->getWebDriver();
        $webDriver->wait(15, 500)->until(
            WebDriverExpectedCondition::stalenessOf($oldPage)
        );
    }

    /**
     * @param string $xPath
     * @throws ElementNotVisibleException
     * @throws \Exception
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function iCanTheSeeElementWithXpath($xPath)
    {
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        $this->getWebDriver()->wait(10, 1000)->until(
            WebDriverExpectedCondition::visibilityOf($element)
        );
        if (!$element->isDisplayed()) {
            throw new ElementNotVisibleException('Element not visible.' . $xPath);
        }
    }

    /**
     * @param string $xPath
     * @param integer $seconds
     */
    public function waitForTheElementToBeVisible($element, $seconds = null){
        if (null === $seconds){
            $seconds = 5;
        }
        $this->getWebDriver()->wait($seconds, 500)->until(WebDriverExpectedCondition::visibilityOf($element));
    }

    /**
     * @param string $xPath
     * @throws ElementNotVisibleException
     */
    public function iCanSeeTheElementsWithXpath($xPath)
    {
        $elements = $this->getWebDriver()->findElements(WebDriverBy::xpath($xPath));
        foreach ($elements as $element) {
            if (!$element->isDisplayed()) {
                throw new ElementNotVisibleException('Element not visible.');
            }
        }
    }

    /**
     * @param string $xPath
     * @throws \Exception
     */
    public function iClickOnTheElementWithXpath($xPath)
    {
        $element = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        if (!$element->isDisplayed()) {
            throw new ElementNotVisibleException('Element is not displayed');
        }
        $element->click();
    }

    /**
     * @param integer $seconds
     */
    public function iWait($seconds)
    {
        $this->getWebDriver()->wait($seconds);
    }

    /**
     * @param string $value
     * @param string $xPath
     * @throws ElementNotVisibleException
     */
    public function iEnterAValueInTheFieldWithXpath($value, $xPath)
    {
        $field = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath));
        if (!$field->isDisplayed()) {
            throw new ElementNotVisibleException('Field not visible.');
        }
        $field->sendKeys($value);
    }

    /**
     * @param string $expectedValue
     * @param string $xPath
     * @throws \Exception
     */
    public function expectValueOfElementWithXpath($expectedValue, $xPath)
    {
        $actualValue = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath))->getText();
        if ($actualValue !== $expectedValue) {
            $message = 'Wrong value in field! Expected value was ' . $expectedValue . ' but actual is ' . $actualValue;
            throw new \RuntimeException($message);
        }
    }

    /**
     * @param string $xPath
     */
    public function iClickTheElementWithXpath($xPath)
    {
        $element = $this->getWebDriver()
            ->findElement(WebDriverBy::xpath($xPath));
        $element->click();
    }

    /**
     * @param RemoteWebElement $element
     */
    public function iClickElement(RemoteWebElement $element)
    {
        $element->click();
    }

    /**
     * @param integer $seconds
     * @param string  $xPath
     * @throws \Exception
     */
    public function iWaitForTheElementWithXpath($seconds, $xPath)
    {
        $this->getWebDriver()->wait($seconds, 1000)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($xPath))
        );
    }

    /**
     * @param RemoteWebElement $element
     */
    public function moveMouseOverElement(RemoteWebElement $element)
    {
        $script = 'var fireEvent = arguments[0];' .
            'var evObj = document.createEvent("MouseEvents");' .
            'evObj.initEvent( "mouseover", true, true );' .
            'fireEvent.dispatchEvent(evObj);';
        $this->getWebDriver()->executeScript($script, [$element]);
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function isLinkReachable($url)
    {
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_NOBODY, true);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 0);
        $response = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $response;
    }

    /**
     * if we test not production server we should replace link
     * to schuhcenter.de within environment variable $TEST_BASE_URL
     *
     * @param string $url
     * @return string
     */
    public function substituteDomain($url)
    {
        $returnUrl = $url;
        if (getenv('TEST_BASE_URL') !== 'http://www.schuhcenter.de') {
            preg_match('/^(http\:\/\/www\.schuhcenter.de)(.*)/', $url, $ref);
            if ($ref !== []) {
                $returnUrl = getenv('TEST_BASE_URL') . $ref[2];
            }
        }
        return $returnUrl;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function canUrlBeChecked($url)
    {
        return strpos('http', (string)$url) !== 0;
    }

    /**
     * @Then each image in the body is displayed
     * @throws \Exception
     */
    public function eachImageInTheBodyDisplayed()
    {
        $brokenImages = [];
        $images = $this->getWebDriver()
            ->findElements(WebDriverBy::xpath('//body//img'));
        foreach ($images as $image) {
            $imageSource = $image->getAttribute('src');
            if ($imageSource === '') {
                $brokenImages[] = 'Empty src tag - ' . $this->generateXPathExpressionToElement($image, '');
                continue;
            }
            $script = 'return typeof arguments[0].naturalWidth !== "undefined" && arguments[0].naturalWidth !== 0';
            $isLoaded = $this->getWebDriver()->executeScript($script, [$image]);
            if (!$isLoaded) {
                $brokenImages[] = $imageSource;
            }
        }
        if ($brokenImages !== []) {
            $message = "\n" . implode("\n", $brokenImages);
            throw new \RuntimeException('Broken images found' . $message);
        }
    }

    /**
     * @param string $xPath
     * @throws \Exception
     */
    public function allLinksInElementAreShown($xPath)
    {
        $links = $this->getWebDriver()
            ->findElements(WebDriverBy::xpath($xPath . '//a'));
        $invisibleLinks = [];
        foreach ($links as $link) {
            if (!$link->isDisplayed()) {
                $invisibleLinks[] = $link->getAttribute('href') . "\t\t" . $link->getText();
            }
        }
        if ($invisibleLinks !== []) {
            $message = implode("\n", $invisibleLinks);
            throw new \RuntimeException('Links are not displayed' . "\n" . $message);
        }
    }

    /**
     * @param string $xPath
     * @throws \Exception
     */
    public function allLinksInElementWork($xPath)
    {
        $linksElements = $this->getWebDriver()
            ->findElements(WebDriverBy::xpath($xPath . '//a'));
        $links = [];
        //To avoid loosing elements during curl operations we add each link to the array
        foreach ($linksElements as $linksElement) {
            $links[] = ['href' => $linksElement->getAttribute('href'), 'text' => $linksElement->getText()];
        }
        $checkedLinks = [];
        $brokenLinks = [];
        foreach ($links as $link) {
            $href = $this->substituteDomain($link['href']);
            $text = $link['text'];
            if (array_key_exists($href, $checkedLinks)) {
                continue;
            }
            if (!$this->canUrlBeChecked($href)) {
                $checkedLinks[$href] = null;
                $brokenLinks[] = $href;
                continue;
            }
            if (!$this->isLinkReachable($href)) {
                $brokenLinks[] = $href . "\t\t" . $text;
                $checkedLinks[$href] = null;
                continue;
            }
            $checkedLinks[$href] = null;
        }
        if ($brokenLinks !== []) {
            $message = implode("\n", $brokenLinks);
            throw new \RuntimeException('Links are broken' . "\n" . $message);
        }
    }

    /**
     * @param string $xPath
     * @throws \RuntimeException
     */
    public function theLinkInTheElementWithXpathWorks($xPath)
    {
        $href = $this->getWebDriver()->findElement(WebDriverBy::xpath($xPath))->getAttribute('href');
        if (!$this->canUrlBeChecked($href)) {
            throw new \RuntimeException('Link could not be checked - ' . $href);
        }
        $hrefWithSubstitutedDomain = $this->substituteDomain($href);
        if (!$this->isLinkReachable($hrefWithSubstitutedDomain)) {
            throw new \RuntimeException('Link is broken - ' . $hrefWithSubstitutedDomain);
        }
    }

    /**
     * @param RemoteWebElement $element
     * @param string           $currentElementXpath
     * @return null|string
     */
    public function generateXPathExpressionToElement(RemoteWebElement $element, $currentElementXpath)
    {
        $elementTag = $element->getTagName();
        if ($elementTag === 'html') {
            return '/html[1]' . $currentElementXpath;
        }
        $parentElement = $element->findElement(WebDriverBy::xpath('..'));
        $childElements = $parentElement->findElements(WebDriverBy::xpath('*'));
        $count = 0;
        foreach ($childElements as $childElement) {
            $childrenElementTag = $childElement->getTagName();
            if ($elementTag === $childrenElementTag) {
                $count++;
            }
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($element == $childElement) {
                $elementPath = '/' . $elementTag . '[' . $count . ']' . $currentElementXpath;
                return $this->generateXPathExpressionToElement($parentElement, $elementPath);
            }
        }
        return null;
    }
}

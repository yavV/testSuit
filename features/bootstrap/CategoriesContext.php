<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\Exception\TimeOutException;

/**
 * This class contains tests related to the categories.
 */
class CategoriesContext extends BaseContext
{
    const FILTERTYPESELECT = 0;
    const FILTERTYPESLIDER = 1;
    const TESTMENU = 0;
    const TESTSUBCATEGORIES = 1;
    const TESTFILTERS = 2;
    const TESTITEMS = 3;
    /**
     * @var array
     */
    private $productsNameBeforeFilter;

    /**
     * @var RemoteWebElement
     */
    private $filterParamsSection;

    /**
     * @var string
     */
    private $filterButtonText;

    /**
     * @var array
     */
    private $currentMenu;

    /**
     * @var string
     */
    private $mainPageTitle;


    /**
     * @Then filters work on every category page
     *
     */
    public function filtersWorkOnEveryCategoryPage()
    {
        $this->runCategoriesTest(self::TESTFILTERS);
    }

    /**
     * @Then if there are subcategories they could be opened
     */
    public function ifThereAreSubcategoriesTheyCouldBeOpened()
    {
        $this->runCategoriesTest(self::TESTSUBCATEGORIES);
    }

    /**
     * @Then there are items at all of categories pages
     */
    public function thereAreItemsAtAllOfCategoriesPages()
    {
        $this->runCategoriesTest(self::TESTITEMS);
    }

    /**
     * @Then all menus work
     * @throws \RuntimeException
     */
    public function allMenusWork()
    {
        $this->runCategoriesTest(self::TESTMENU);
    }

    /**
     * This function compares goods before and after applying filter
     *
     * @param RemoteWebElement[] $productsAfterFilter
     * @return bool
     */
    public function areTheSameProductsBeforeAndAfterFilter($productsAfterFilter)
    {

        $productsCountAfterFilter = count($productsAfterFilter);
        $productsCountBeforeFilter = count($this->productsNameBeforeFilter);
        if ($productsCountAfterFilter !== $productsCountBeforeFilter) {
            return false;
        }
        for ($i = 0; $i < $productsCountAfterFilter; $i++) {
            if (null === $this->productsNameBeforeFilter[$i]) {
                return false;
            }
            $productNameAfterFilter = $productsAfterFilter[$i]->findElement(WebDriverBy::xpath('.//a[@class="productlink"]'))->getText();
            if ($this->productsNameBeforeFilter[$i] !== $productNameAfterFilter) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function applyFilter($type)
    {
        $selectedFilterTitle = '--';
        switch ($type) {
            case self::FILTERTYPESELECT:
                $selectsFilter = $this->filterParamsSection->findElements(WebDriverBy::xpath('.//span[contains(@class,"value")]'));
                if ($selectsFilter === []) {
                    return "No elements in $this->filterButtonText filter on page" . $this->currentMenu['title'];
                }
                $selectedFilterTitle = $selectsFilter[0]->getText();
                $selectsFilter[0]->click();
                $filterAnwendenButton = $this->filterParamsSection->findElement(WebDriverBy::xpath('.//button[contains(text(), "Filter anwenden")]'));
                if (null === $filterAnwendenButton) {
                    return "No button named 'Filter anwenden' in the $this->filterButtonText filter on page" . $this->currentMenu['title'];
                } elseif (!$filterAnwendenButton->isDisplayed()) {
                    return "The button named 'Filter anwenden' is not visible in the $this->filterButtonText filter on page" . $this->currentMenu['title'];
                }
                break;
            case self::FILTERTYPESLIDER:
                break;

        }
        $filterAnwendenButton->click();

        $this->iWaitForThePageToBeChanged();

        $productsXpath = '//div[@class="product-data"]';
        $productsAfterFilter = $this->getWebDriver()->findElements(WebDriverBy::xpath($productsXpath));
        if ($productsAfterFilter === []) {
            return 'No goods on page' . $this->currentMenu['title'] . ' after filter ' . $selectedFilterTitle;
        }
        echo "$selectedFilterTitle compare products \n";
        if ($this->areTheSameProductsBeforeAndAfterFilter($productsAfterFilter)) {
            return "Filter $this->filterButtonText return the same goods with parameter '$selectedFilterTitle'";
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function checkMenu()
    {
        if ($this->getWebDriver()->getTitle() === $this->mainPageTitle) {
            return $this->currentMenu['title']
                . ' ' . $this->currentMenu['link']
                . ' The page title did not changed';
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function checkItemsOnThePage()
    {
        try {
            $productsXpath = '//div[@class="product-data"]';
            $products = $this->getWebDriver()->findElements(WebDriverBy::xpath($productsXpath));
            if (count($products) === 0) {
                return $this->currentMenu['title'] . ' - No products at the category page';

            }
            $itemNum = mt_rand(0, count($products) - 1);

            if (!$products[$itemNum]->isDisplayed()) {
                return $this->currentMenu['title'] . ' - There is not visible product at the category page';
            }

        } catch (Exception $e) {
            return 'Error on page ' . $this->currentMenu['title'] . ' ' . $e->getMessage();
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function checkSubcategories()
    {
        try {
            $subcategoriesXpath = '//section[contains(@class,"catlist")]//a';
            $subcategories = $this->getWebDriver()->findElements(WebDriverBy::xpath($subcategoriesXpath));
            if (count($subcategories) === 0) {
                return $this->currentMenu['title'] . ' - No subcategories at the category page';
            }
            $subcategoryNum = mt_rand(0, count($subcategories) - 1);
            $categoryPageTitle = $this->getWebDriver()->getTitle();
            $this->getWebDriver()->get($subcategories[$subcategoryNum]->getAttribute('href'));
            $this->iWaitForThePageToBeLoaded();
            if ($this->getWebDriver()->getTitle() === $categoryPageTitle) {
                return $this->currentMenu['title']
                    . ' - The subcategory page with title "'
                    . $subcategories[$subcategoryNum]->getText()
                    . '" could not be shown';
            }
        } catch (Exception $e) {
            return $this->currentMenu['title'] . " - " . $e->getMessage();
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function checkFilters()
    {
        $filterXpath = '//div[@class="store_set_filter"]//div[contains(@class,"collapse-filter") and not(contains(@class,"visible-sm-large"))]';
        $filterElements = $this->getWebDriver()->findElements(WebDriverBy::xpath($filterXpath));
        $countFilterElements = count($filterElements);

        if ($filterElements === []) {
            return 'No filters found on page ' . $this->currentMenu['title'];
        }
        for ($i = 0; $i < $countFilterElements; $i++) {
            try {
                $this->getWebDriver()->get($this->currentMenu['link']);
                //$this->iWaitForThePageToBeChanged();
            } catch (Exception $e) {
                return 'Page was not opened - ' . $this->currentMenu['title'] . "\t\t\t" . $this->currentMenu['link'] . "\n" . $e->getMessage();
            }
            $productsXpath = '//div[@class="product-data"]//div[@class="over-links"]/a[@class="productlink"]';
            $productsBeforeFilter = $this->getWebDriver()->findElements(WebDriverBy::xpath($productsXpath));
            if ($productsBeforeFilter === []) {
                return 'No goods on page' . $this->currentMenu['title'] . ' before search';
            }
            $filterElements = $this->getWebDriver()->findElements(WebDriverBy::xpath($filterXpath));
            $filterElement = $filterElements[$i];
            $filterButton = $filterElement->findElement(WebDriverBy::xpath('.//button'));

            $filterButton->click();

            $this->filterButtonText = $filterButton->getText();
            $this->filterParamsSection = $filterElement->findElement(WebDriverBy::xpath('.//div'));
            try {
                $this->waitForTheElementToBeVisible($this->filterParamsSection);
            } catch (TimeOutException $e) {
                return 'Filter with title '
                    . $this->filterButtonText
                    . "does not work on page " . $this->currentMenu['title'];
            }
            $this->productsNameBeforeFilter = [];
            foreach ($productsBeforeFilter as $productBeforeFilter) {
                $this->productsNameBeforeFilter[] = $productBeforeFilter->getText();
            }
            $selectType = self::FILTERTYPESELECT;

            if ($filterButton->getText() !== "Preisspanne") {
                return $this->applyFilter($selectType);
            }
        }
    }

    /**
     * @param $testType
     */
    public function runCategoriesTest($testType)
    {
        $xpathMenu = "//div[@id='mainnavigation']/div[@class='container']/ul[@class='nav nav-pills unstyled']/li";
        /** @var RemoteWebElement[] $menus */
        $menus = $this->getWebDriver()
            ->findElements(WebDriverBy::xpath($xpathMenu));
        //Save links to avoid loosing the menu elements
        $menuLinks = [];
        foreach ($menus as $menu) {
            $menuAnchor = $menu->findElement(WebDriverBy::xpath('.//a'));
            $menuUrl = $this->substituteDomain($menuAnchor->getAttribute('href'));
            $menuLinks[] = ['title' => $menuAnchor->getText(), 'link' => $menuUrl];
        }
        $errors = [];
        foreach ($menuLinks as $menuLink) {
            $this->currentMenu = $menuLink;
            $this->mainPageTitle = $this->getWebDriver()->getTitle();
            try {
                $this->getWebDriver()->get($this->currentMenu['link']);
                $this->iWaitForThePageToBeLoaded();

                //debug
                $_categoryPageTitle = $this->getWebDriver()->getTitle();
                echo $_categoryPageTitle . "\n";

                //$this->iWaitForThePageToBeChanged();
            } catch (Exception $e) {
                $errors[] = 'Page was not opened - ' . $this->currentMenu['title'] . "\t\t\t" . $this->currentMenu['link'] . "\n" . $e->getMessage();
                continue;
            }
            switch ($testType) {
                case self::TESTMENU:
                    $checkResults = $this->checkMenu();
                    break;
                case self::TESTSUBCATEGORIES:
                    $checkResults = $this->checkSubcategories();
                    break;
                case self::TESTFILTERS:
                    $checkResults = $this->checkFilters();
                    break;
                case self::TESTITEMS:
                    $checkResults = $this->checkItemsOnThePage();
                    break;



            }

            if (null !== $checkResults) {
                $errors[] = $checkResults;
            }
        }
        if ($errors !== []) {
            $message = "\n" . implode("\n\n", $errors);
            throw new RuntimeException($message);
        }
    }
}
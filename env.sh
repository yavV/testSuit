unset SELENIUM_HUB_URL
 unset TEST_BASE_URL
 unset TEST_OPTIONS

export SELENIUM_HUB_URL='http://localhost:4444/wd/hub'
export TEST_BASE_URL='https://benchy-dev.testbox4.me'
#export TEST_OPTIONS='{"resolution": "1024x768", "browser": "chrome"}'

#export TEST_OPTIONS='{"resolution": "1024x768", "browser": "firefox", "webdriver.log.driver": "error", "webdriver.log.file": "/tmp/firefox_console"}'
export TEST_OPTIONS='{"resolution": "1024x768", "browser": "chrome", "webdriver.log.driver": "error", "webdriver.log.file": "/tmp/chrome_console"}'
 #"app.update.enabled": "false", "app.update.auto": "false"}'
#, "app.update.enabled": "false", "app.update.auto": "false"}'

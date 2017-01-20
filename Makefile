
TARGET = check-for-device-or-group-usage
VERSION = $(shell git tag -l | tail -1)
INSTALL_DEFAULT = install.phar
INSTALL_FILE = install_$(TARGET)_$(VERSION).phar

BUILD_DIR = bin
BIN_TARGET = bin/CheckForDeviceOrGroupUsage.phar

INSTALLER = /usr/local/bin/pspm
INSTALLER_OPT = uise -c installer.ini -B
BOX = $(HOME)/bin/box

GITHOME = $(HOME)/git

export PHPRC = $(shell pwd)

SOURCES = $(wildcard src/*.php)
AUTOLOAD = vendor/autoload.php

$(INSTALL_FILE): $(INSTALL_DEFAULT)
	mv $< $@

$(INSTALL_DEFAULT): $(BIN_TARGET)
	rm -f $@
	$(INSTALLER) $(INSTALLER_OPT) $(BUILD_DIR)

$(BIN_TARGET): $(BOX) $(INSTALLER)

bin/%: bin/%.phar
	mv -f $^ $@

%.phar: $(AUTOLOAD) $(SOURCES) box.json
	box build

vendor/autoload.php: composer.json
	composer install --no-dev

clean:
	rm -rf install_$(TARGET)_v* $(BIN_TARGET) vendor

Makefile:
	:

$(INSTALLER): $(GITHOME)/ps-package-manager/pspm
	cd $(<D); ./install.sh

$(GITHOME)/ps-package-manager/pspm:
	test -d $(GITHOME) || mkdir $(GITHOME)
	cd $(GITHOME); git clone git@git.sevone.com:jguglev/ps-package-manager.git

$(BOX):
	curl -LSs https://box-project.github.io/box2/installer.php | php
	mv -f ./box.phar $@

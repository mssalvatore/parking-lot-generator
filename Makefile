SOURCE_DIR = bin

all: | composer make_bin box

composer:
	composer install

make_bin:
	@mkdir -p $(SOURCE_DIR)/

box:  | make_bin
	box build

clean:
	@rm -rf $(SOURCE_DIR)/*

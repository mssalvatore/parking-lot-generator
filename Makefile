SOURCE_DIR = bin
    
all: | make_bin box 
    
box: 
	box build
    
clean:
	@rm -rf $(SOURCE_DIR)/*
    
make_bin:
	@mkdir -p $(SOURCE_DIR)/

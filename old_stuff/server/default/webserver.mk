c_src=../src/
c_obj=./obj_webserver/

c_objects = \
$(c_obj)server.o

$(c_obj)%.o : $(c_src)%.cpp
	$(CC) $(CFLAGS) $< -o $@

web : $(c_objects)
	$(CC) -o tdv-web $(c_objects) $(LFLAGS)

clean_web :
	rm -rf $(c_obj)* tdv-web

s_src=../src/
s_obj=./obj_simulator/

s_objects = \
$(s_obj)move.o \
$(s_obj)world.o \
$(s_obj)simulator.o \

$(s_obj)%.o : $(s_src)%.cpp
	$(CC) $(CFLAGS) $< -o $@

sim : $(s_objects)
	$(CC) -o tdv-sim $(s_objects) $(LFLAGS)

clean_sim :
	rm -rf $(s_obj)* tdv-sim

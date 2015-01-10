/*
 * server.cpp
 *
 *  Created on: 8 May 2010
 *      Author: Dave
 */

#include <pthread.h>
#include <string>
#include <iostream>

#include "comms.h"
#include "world.h"

using namespace std;

#define NUM_HANDLES 3

struct tHandler {
	int id;
	pthread_t t;
	bool available;
	void *retVal;
	Comms com;
	pthread_mutex_t *available_mutex;
	pthread_cond_t *available_cond;
	tdv::World *world;
};

/**
 * Path addition thread
 *   get message from client
 *   check validity of start / end points (can give: failed, modified)
 *   add path to world (world obj) !! there's all sorts of consistency / concurency issues here !!
 *   return success indicator ... maybe rendering data (xml) ?
 */
void *addPath( void *ptr )
{
	stringstream outMsg;
	string inMsg;
	tHandler *h = (tHandler *)ptr;
	int rv;

	h->com.cRecv( inMsg );
	cout << ". thread " << h->id << " got " << inMsg << endl;

	rv = h->world->addMove( 1, 1, 1, 1, 1);

	outMsg << "Welcome to The Dark Vortex (" << inMsg << ") from " << h->id;
	cout << ". thread " << h->id << " sending response:\n. thread " << h->id << " " << outMsg.str() << endl;

	if (h->com.cSend( outMsg.str() ) == -1) {
		perror("send");
	}
	h->com.cDisconnect();

	pthread_mutex_lock( h->available_mutex );
	h->available = true;
	pthread_cond_signal( h->available_cond );
	pthread_mutex_unlock( h->available_mutex );
	// Theoretically this thread could be re-started from main while at this point
	// I've looked at pthread_cleanup_push (errored) and pthread_cancel (from main, failed)
	// but neither seems to work. Can't see any other way so just leaving this in
	// and hoping it doesn't cause any problems :-/
	// Maybe if/when I do thread pooling stuff this will get resolved (as won't be exiting)
	pthread_exit( h->retVal );
}

/**
 * periodically (every second?) instruct the world to update all paths based on their meetings
 *   though I'm probably going to have an "attack rate" attrib
 *   so the world obj will need to maintain a queue of "who's next"
 * work out how long to wait before the next pulse
 * !we could probably split the work between multiple threads if we get each one
 *  to update only some section of the meeting queue ? may not be worth it... will need to test
 */
void *worldSim( void *ptr )
{
	tHandler *h = (tHandler *)ptr;
	while(1) {
		h->world->simIncidents();
		usleep( 2000000 ); // wait until next pulse needed (needs to do gettimeofday and some maths to work out a proper number here)
	}
}

// START HERE -
// Now 2-way comms is working, make a way to encapsulate the co-ord data (wrap and unwrap in comms.h)
// Then I reckon it's on to making use of those data as they come in
// keep all game logic out of this "controller"y function set

int main(void)
{
	int rv;
	Comms com;
	tdv::World *world = new tdv::World();
	if( world->getStatus() != WORLD_OK ) {
		cout << world->getError();
		return 1;
	}

	// initialise mutexes, conditions and attributes.
	// mutexes and conditions are pointers so we can share them with the handler threads
	// and have consistent look of code (no "&" in lock calls, etc in main or handlers)
	pthread_mutex_t *available_mutex = new pthread_mutex_t();
	pthread_mutex_init( available_mutex, NULL );
	pthread_cond_t *available_cond = new pthread_cond_t();
	pthread_cond_init( available_cond, NULL );

	pthread_attr_t *attr = new pthread_attr_t();
	pthread_attr_init( attr );
	pthread_attr_setdetachstate( attr, PTHREAD_CREATE_DETACHED );

	// *** this list of thread slots would probably be better implimented as a thread pool
	// avoiding the create / delete overheads should improve performance
	// ... in fact quite a lot of the main loop here should prob be librarified (pool handler class)
	tHandler h[NUM_HANDLES];
	for( int i = 0; i < NUM_HANDLES; i++ ) {
		h[i].id = i;
		h[i].available = true;
		h[i].available_mutex = available_mutex;
		h[i].available_cond  = available_cond;
		h[i].world = world;
	}
	cout << "mutex: " << available_mutex << endl;

	tHandler simThread;
	simThread.id = -1;
	simThread.available = true;
	simThread.available_mutex = available_mutex;
	simThread.available_cond  = available_cond;
	simThread.world = world;
	rv = pthread_create( &simThread.t, attr, worldSim, (void *)&simThread );

	rv = com.cConnect(true); // connect and listen. ready for connections
	printf("server: waiting for connections...\n");

	int curHandle = NUM_HANDLES, nextHandle = -1;
	bool foundHandle;
	while (1) { // main accept() loop
		// find the next available thread starting from just after the current
		// (the next thread will probably be among the oldest, so most likely to have finished)
		foundHandle = false;
		while( !foundHandle ) {
			cout << "availability: ";
			for( int j=0; j < NUM_HANDLES; j++ ) {
				cout << j << ":" << h[j].available << ", ";
			}
			cout << endl;

			pthread_mutex_lock( available_mutex );
			for( nextHandle = curHandle + 1; nextHandle < NUM_HANDLES; nextHandle++ ) {
				if( h[nextHandle].available == true ) {
					foundHandle = true;
					break;
				}
			}
			if( !foundHandle ) {
				for( nextHandle = 0; nextHandle <= curHandle; nextHandle++ ) {
					if( h[nextHandle].available == true ) {
						foundHandle = true;
						break;
					}
				}
			}

			// if we failed to find an available handle wait for some thread to signal that we should check again
			if( !foundHandle ) {
				cout << "waiting for handle... ";
				pthread_cond_wait( available_cond, available_mutex );
			}
			else {
				cout << "found handle";
				h[nextHandle].available = false;
			}
			pthread_mutex_unlock( available_mutex );
		}
		// nextHandle now has the next available handle, or the current handle if we didn't find a free one
		curHandle = nextHandle;
		cout << ", using thread: " << curHandle << endl;
		rv = com.cAccept(h[curHandle].com);
		if (rv == -1) {
			perror("accept");
			continue;
		}

		// this is debug output... should prob be commented out
		cout << "server: got a connection - " << curHandle << " - from " << h[curHandle].com.cGetInAddr() << endl;

		rv = pthread_create( &h[curHandle].t, attr, addPath, (void *)&h[curHandle] );
	}

	return 0;
}

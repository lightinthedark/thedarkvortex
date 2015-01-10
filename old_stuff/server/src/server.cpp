/*
 * server.cpp
 *
 *  Created on: 10 May 2010
 *      Author: Dave
 *
 * Listens to the WAN-exposed port for HTTP requests from our app
 * Upon accepting a connection it spawns (or finds in a poool) a thread "R" to deal with the incoming request
 * R: Verify the message source and player id. Give error response and close connection if appropriate
 * R: If the request can be immediately fulfilled we make the requested call and reply with the relevant data
 * R: If the request wants to sit and wait for updates we pass the connection on to persistent thread "L"
 * L: hold an open connection to the simulator and a bunch of open connections to clients
 * L: when the simulator gives us messages for players match these up to connections and pass on the message
 */

#include <pthread.h>
#include <string>
#include <iostream>

#include "comms.h"

#define MAXREQUESTSIZE 1000 // max number of bytes we can get in a single request

using namespace std;


void killConnection( Comms com )
{
	com.cDisconnect();
}

void *serveRequest( void *arg )
{
	void *retVal;
	Comms incoming = *(Comms *)arg;
	string inMsg, request;

	// receive message
	int numbytes = 0, rlen = 0;
	bool finished = false;
	do {
		rlen += ( numbytes = incoming.cRecv( inMsg ) );
		request.append( inMsg );

		if( numbytes == -1 ) {
			// something's gone wrong so bail out
			killConnection( incoming );
			pthread_exit( retVal );
		}

		if( ( numbytes == 0 )
		 || ( request.find( "\n\n" ) != string::npos )
		 || ( request.find( "\r\n\r\n" ) != string::npos ) ) {
			finished = true;
		}
	} while( ( rlen < MAXREQUESTSIZE ) && ( !finished ) );

	// check message is a valid HTTP, tdv request from a trustworthy source
	bool ok = true;
	// reject badly formed requests
	// **** START HERE
	// request first line must be of format:
	// GET /<simId>/info_report.tdv?player=<playerId>&token=<token>
	// GET /<simId>/info_wait.tdv?player=<playerId>&token=<token>
	// POST /<simId>/group_move.tdv?player=<playerId>&token=<token> ### POST DATA: ### group=<groupId>&time=<timestamp>&destX=int&destY=int , maybe more
	// POST /<simId>/group_assign.tdv?player=<playerId>&token=<token> ### POST DATA: ### group=<groupId>&unit=<unitId> , maybe more
	// where key=value pairs after the ? can be in any order and is not limited to those listed (others will be ignored)
	// token will need to be checked against the db to ensure it's the user's currently valid token
	// ... all this is quite a lot of stuff so will prob be best in a separate function
	// that function prob will just verify it's ok to process the request, and extract the action and player id
	// then another section / function to parse any post data and send off calls to act on the request
	if( (request.find( "GET" ) == string::npos ) ) {
		ok = false;
	}
	if( ( request.find( "\n\n" ) == string::npos )
	 && ( request.find( "\r\n\r\n" ) == string::npos ) ) {
		ok = false;
	}
	if( !ok ) {
		cout << "request no good" << endl;
		killConnection( incoming );
		pthread_exit( retVal );
	}


	// decode and validate message

	// perform requested action (will need to connect to simulator and send command) if any

	// reply with immediate data
	cout << "client received: " << request << endl;
	string response;
	response.assign( "HTTP/1.0 200 OK\r\nContent-Type: text/html\r\n\r\nMonkey\r\n\r\n" );
	cout << "sending: " << response << endl;
	incoming.cSend( response );

	// add connection to long-term listeners if required, otherwise disconnect
	incoming.cDisconnect();

	// return success indicator
	pthread_exit( retVal );
}

int main(int argc, char *argv[])
{
    Comms com;
	int r = com.cConnect( "localhost", "8080", true);
	pthread_attr_t *attr = new pthread_attr_t();
	pthread_attr_init( attr );
	pthread_attr_setdetachstate( attr, PTHREAD_CREATE_DETACHED );

	int rv;
	Comms incoming;
	pthread_t t;
	while( 1 ) { // main accept() loop
//		&incoming = new Comms();
		rv = com.cAccept( incoming );
		if (rv == -1) {
			perror("accept");
			continue;
		}

		// make a thread to deal with the connection
		rv = pthread_create( &t, attr, serveRequest, (void *)&incoming );
	}


//	if( argc < 2 ) {
//		cout << "please supply at least one argument";
//		return 1;
//	}
//	cout << "count in argc = " << argc << endl;
//	for(int i = 0; i < argc; i++)
//	cout << "argv[" << i << "] = " << argv[i] << endl;
//
//    Comms com;
//    int r = com.cConnect();
//    if( r != 0 ) {
//    	return r;
//    }
//
//    // debug
//	cout << "client: connecting to " << com.cGetInAddr() << endl;
//
//	com.cSend( argv[1] );
//
//    string reply;
//    com.cRecv( reply );
//    cout << "client received: " << reply << endl;

    return 0;
}

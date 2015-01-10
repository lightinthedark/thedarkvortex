/*
 * world.h
 *
 *  Created on: 15 May 2010
 *      Author: Dave
 */

#ifndef WORLD_H_
#define WORLD_H_

#include <pthread.h>
#include <iostream>
#include <sstream>
#include <libpq-fe.h>

#include "move.h"

#define WORLD_OK 0
#define WORLD_NO_DB 1

namespace tdv
{

class World
{
private:
	/* mutexes / conditions */
	pthread_mutex_t incident_mutex;
	pthread_cond_t incident_cond;
	Move *curMoves;
	int *grid;
	int *incidents;
	int status;
	const char *conninfo;
	std::stringstream error;
	PGconn *conn;

public:
	World();
	virtual ~World();

	int getStatus();
	std::string getError();
	int addMove( int group, int t1, int x2, int y2, int s );
	int simIncidents();
};

}

#endif /* WORLD_H_ */
